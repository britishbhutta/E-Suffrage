<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceIssueMail;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Tariff;
use App\Models\PurchasedTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Stripe\Exception\ApiErrorException;


class StripeController extends Controller
{
    // public function store(Request $request)
    // {
    //     $rules = [
    //         'stripeToken'   => 'required|string',
    //         // 'selectedTariffId' => 'required|exists:tariffs,id',
    //         // Billing info
    //         'cardholder_name' => 'required',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }
       
    //     // Verify Cloudflare Turnstile
    //     $turnstileResponse = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
    //         'secret' => config('services.turnstile.secret_key'),
    //         'response' => $request->input('cf-turnstile-response'),
    //         'remoteip' => $request->ip(),
    //     ]);

    //     if (!($turnstileResponse->json('success') ?? false)) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'CAPTCHA verification failed. Please try again.'
    //         ], 422);
    //     }
    //     $validated = $validator->validated();
    //     // $country_id = (int) filter_var($validated['country'], FILTER_SANITIZE_NUMBER_INT);
    //     // $country = Country::find($country_id);
        
    //     if(session()->has('booking_id')){
    //         $booking = Booking::find(session('booking_id'));
    //     }
    //     $selectedTariff = Tariff::find($booking->tariff_id);

    //     $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    //     $charge = $stripe->charges->create([
    //         'amount' => $selectedTariff->price_cents,
    //         'currency' => $selectedTariff->currency,
    //         'source' => $request->stripeToken,
    //     ]);

    //     $validated = $validator->validated();
    //     try {
    //     // $booking = new Booking;
    //     // $booking->tariff_id        = $request->selectedTariffId;
    //     // $booking->user_id          = auth()->id();
    //     $booking->price            = $selectedTariff->price_cents / 100;
    //     $booking->currency         = $selectedTariff->currency;
    //     $booking->transaction_id   = $charge->id;
    //     $booking->payment_status   = $charge->status;
    //     $booking->payment_method   = 'stripe';
    //     $booking->save();
    //     if($booking->invoice_issue == 1){
    //         $user = Auth::user();
    //         Mail::to($booking->email)->send(new InvoiceIssueMail($user, $booking, $selectedTariff));
    //     }
        
    //         $totalVotes = (int) ($selectedTariff->available_votes ?? 0);
    //         $purchased = PurchasedTariff::create([
    //             'booking_id'     => $booking->id,
    //             'tariff_id'      => $selectedTariff->id,
    //             'user_id'        => auth()->id() ?: null,
    //             'total_votes'    => $totalVotes,
    //             'remaining_votes' => $totalVotes,
    //             'token'          => (string) Str::uuid(),
    //             'is_active'      => true,
    //         ]);
    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Payment processed successfully but failed to create purchased tariff: ' . $e->getMessage(),
    //             'booking_id' => $booking->id,
    //         ]);
    //     }

    //     session([
    //         'voting.booking_id' => $booking->id,
    //         'voting.purchased_tariff_id' => $purchased->id,
    //         'voting.selected_tariff' => $selectedTariff->id,
    //     ]);

        
        

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Payment processed successfully!',
    //         'booking_id' => $booking->id,
    //         'purchased_tariff_id' => $purchased->id,
    //     ]);
    // }
 
    public function store(Request $request)
    {
        $rules = [
            'stripeToken'     => 'required|string',
            'cardholder_name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify Turnstile
        $turnstileResponse = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret'   => config('services.turnstile.secret_key'),
            'response' => $request->input('cf-turnstile-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!($turnstileResponse->json('success') ?? false)) {
            return response()->json([
                'status' => 'error',
                'message' => 'CAPTCHA verification failed. Please try again.'
            ], 422);
        }

        $booking = session()->has('booking_id') ? Booking::find(session('booking_id')) : null;
        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found in session'
            ], 404);
        }

        $selectedTariff = Tariff::find($booking->tariff_id);
        if (!$selectedTariff) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tariff not found'
            ], 404);
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        if($booking->media_ad_id){
            //api price
            $selectedTariff->price_cents = 9000;
        }
        try {
            // Try to charge
            $charge = $stripe->charges->create([
                'amount'   => (int) $selectedTariff->price_cents,
                'currency' => $selectedTariff->currency,
                'source'   => $request->stripeToken,
                'description' => 'Payment for booking #'.$booking->id,
            ]);
        } catch (ApiErrorException $e) {
            // Stripe rejected it before processing
            \Log::error('Stripe API error: '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed: '.$e->getMessage(),
            ], 402); // 402 Payment Required
        }
        if ($charge->status == 'succeeded'){
            // save logs
            $userId = Auth::id();
            $userName = Auth::user()->first_name .' '. Auth::user()->last_name;
            $description = $userName . ' Has Paid ' . $charge->amount/100 . $charge->currency . ' For Booking id: '. $booking->id;
            $action = 'Payment';
            $module = 'Creation Of Voting Form';
            activityLog($userId, $description,$action,$module);
        }
        // ✅ Only continue if payment succeeded
        if ($charge->status !== 'succeeded') {
            \Log::warning('Stripe charge not successful', ['charge' => $charge]);
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed. Status: '.$charge->status,
            ], 402);
        }

        try {
            // Update booking after successful payment
            $booking->price          = $selectedTariff->price_cents / 100;
            $booking->currency       = $selectedTariff->currency;
            $booking->transaction_id = $charge->id;
            $booking->payment_status = $charge->status; // should be 'succeeded'
            $booking->payment_method = 'stripe';
            $booking->save();

            if ($booking->invoice_issue == 1) {
                $user = Auth::user();
                Mail::to($booking->email)->send(new InvoiceIssueMail($user, $booking, $selectedTariff));
            }
            if(!$booking->media_ad_id){
                $totalVotes = (int) ($selectedTariff->available_votes ?? 0);
                $purchased = PurchasedTariff::create([
                    'booking_id'      => $booking->id,
                    'tariff_id'       => $selectedTariff->id,
                    'user_id'         => auth()->id() ?: null,
                    'total_votes'     => $totalVotes,
                    'remaining_votes' => $totalVotes,
                    'token'           => (string) Str::uuid(),
                    'is_active'       => true,
                ]);
            }

        } catch (\Throwable $e) {
            \Log::error('Booking update error: '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Payment succeeded, but booking update failed: '.$e->getMessage(),
            ], 500);
        }

        session([
            'voting.booking_id'        => $booking->id,
            'voting.purchased_tariff_id' => $purchased->id ?? '',
            'voting.selected_tariff'   => $selectedTariff->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment processed successfully!',
            'booking_id' => $booking->id,
            'purchased_tariff_id' => $purchased->id ?? '',
        ]);
    }


}
