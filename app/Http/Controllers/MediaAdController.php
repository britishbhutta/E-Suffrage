<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tariff;
use Illuminate\Http\Request;

class MediaAdController extends Controller
{
    public function storeMediaAd(Request $request){
        $tariff_id = Tariff::where('title','Tariff_Unlimited')->value('id');
        $booking = Booking::find(session('booking_id'));
        if(empty($booking->media_ad_id)){   
            $booking->tariff_id = $tariff_id;
            $booking->media_ad_id = $request->media_ad_id;
            $booking->update();
        }
        return redirect()->route('voting.create.step', ['step' => 3]);
    }
}
