<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     // Validate hCaptcha response exists
    //     // $request->validate([
    //     //     'h-captcha-response' => 'required',
    //     // ]);

    //     // Verify hCaptcha with API
    //     $hcaptchaResponse = Http::asForm()->post('https://hcaptcha.com/siteverify', [
    //         'secret'   => env('HCAPTCHA_SECRET'),
    //         'response' => $request->input('h-captcha-response'),
    //         'remoteip' => $request->ip(),
    //     ]);

    //     if (!($hcaptchaResponse->json('success') ?? false)) {
    //         return back()
    //             ->withErrors(['h-captcha-response' => 'hCaptcha verification failed. Please try again.'])
    //             ->withInput($request->except('password'));
    //     }

    //     // Proceed with Laravel default login
    //     $request->authenticate();
    //     $request->session()->regenerate();


        

    //     $user = $request->user();
        
    //     // Check if user's email is verified before allowing login
    //     if (!$user->hasVerifiedEmail()) {
    //         // Log the user out immediately
    //         \Illuminate\Support\Facades\Auth::logout();
    //         $request->session()->invalidate();
    //         $request->session()->regenerateToken();
            
    //         // Redirect to verification page with error message
    //         return redirect()->route('verification.notice')
    //             ->with('email_for_verification', $user->email)
    //             ->with('error', 'Please verify your email address before logging in. A verification code has been sent to your email.');
    //     }
    //     // save logs
    //     $userId = Auth::id();
    //     $userName = Auth::user()->first_name .' '. Auth::user()->last_name;
    //     $userRole = Auth::user()->role == 1? 'Voter': 'Creator';
    //     $description = $userName. ' Logged In Successfully as '. $userRole;
    //     $action = 'Logged In';
    //     $module = 'Authentication';
    //     activityLog($userId, $description,$action,$module);
    //     return redirect()->intended(route('dashboard'));
    // }
public function store(LoginRequest $request): RedirectResponse
{
    $hcaptchaResponse = Http::asForm()->post('https://hcaptcha.com/siteverify', [
        'secret'   => env('HCAPTCHA_SECRET'),
        'response' => $request->input('h-captcha-response'),
        'remoteip' => $request->ip(),
    ]);

    if (!($hcaptchaResponse->json('success') ?? false)) {
        return back()
            ->withErrors(['h-captcha-response' => 'hCaptcha verification failed. Please try again.'])
            ->withInput($request->except('password'));
    }

    $user = User::withTrashed()
        ->where('email', $request->email)
        ->first();

    if ($user && $user->deleted_at !== null) {
        return back()
            ->withErrors(['email' => 'Your account has been blocked by the administrator. Please contact the admin for further details or assistance.'])
            ->withInput($request->except('password'));
    }

    $request->authenticate();
    $request->session()->regenerate();

    $user = $request->user();

    if ($user->role == 3) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withErrors(['email' => 'Admin not login from here'])
            ->withInput($request->except('password'));
    }

    if (!$user->hasVerifiedEmail()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('verification.notice')
            ->with('email_for_verification', $user->email)
            ->with('error', 'Please verify your email address before logging in.');
    }

    $userId = Auth::id();
    $userName = $user->first_name . ' ' . $user->last_name;
    $userRole = $user->role == 1 ? 'Voter' : 'Creator';

    activityLog(
        $userId,
        $userName . ' Logged In Successfully as ' . $userRole,
        'Logged In',
        'Authentication'
    );

    return redirect()->intended(route('dashboard'));
}




    /**
     * Destroy an authenticated session.
     */
    public function destroy(\Illuminate\Http\Request $request): RedirectResponse
    {
        // save logs
        $userId = Auth::id();
        $userName = Auth::user()->first_name .' '. Auth::user()->last_name;
        $userRole = Auth::user()->role == 1? 'Voter': 'Creator';
        $description = $userName. ' Logged Out Successfully as '. $userRole;
        $action = 'Logged Out';
        $module = 'Authentication';
        \Illuminate\Support\Facades\Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // save logs
        activityLog($userId, $description,$action,$module);
        return redirect()->route('login');
    }
}
