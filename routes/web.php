<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MediaAdController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TermConditionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\CreaterController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\TariffController;

/*
|--------------------------------------------------------------------------
| Public & auth routes
|--------------------------------------------------------------------------
*/

// Setup Wizard

Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
Route::post('/setup/test', [SetupController::class, 'testConnection'])->name('setup.test');
Route::post('/setup/save', [SetupController::class, 'save'])->name('setup.save');
Route::get('/setup/complete', function(){return view('setup.complete');});
Route::post('/setup/create-database', [SetupController::class, 'createDatabase'])->name('setup.createDatabase');

// Root: role chooser page (public)


Route::middleware(['setup'])->group(function () {
    Route::get('/', [DashboardController::class,'index'])->name('home');
    Route::get('/join', function () {
        return view('auth.choose-role');
    })->name('join');
    Route::get('/t&cJoineVoting',[TermConditionsController::class, 'joinEVoting'])->name('t&cJoineVoting');
        Route::get('/t&cRegistration',[TermConditionsController::class, 'registrationEVoting'])->name('t&cRegistration');

    Route::get('/join-media-ad', function () {
    return view('auth.media-ad-role');
    })->name('join.media.ad');
    // include Breeze / Fortify auth routes (login/register/password/etc.)
    require __DIR__ . '/auth.php';

    // Public routes for Google OAuth
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google.login');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');

    // Public Email verification routes (code-based verification)
    Route::get('email/verify', [EmailVerificationController::class, 'show'])
        ->name('verification.notice');

    Route::post('email/verify', [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');

    Route::post('email/resend', [EmailVerificationController::class, 'resend'])
        ->name('verification.resend');

    // Public API route to check voting status (for auto-refresh functionality)
    Route::get('/api/voting/{token}/status', [VotingController::class, 'checkVotingStatus'])
        ->name('voting.status');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    });

    Route::middleware(['auth', 'verified','role:2'])->group(function () {

        Route::post('store-media-ad',[MediaAdController::class,'storeMediaAd'])->name('store.media.ad');
        
        Route::match(['get', 'post'], '/voting/create/step/{step}', [VotingController::class, 'step'])
            ->whereNumber('step')
            ->name('voting.create.step');
        Route::get('createNewVotingForm',[VotingController::class, 'createNewVotingForm'])->name('create.new.voting.form');
        // Realized votings list
        Route::get('/realized', [VotingController::class, 'realized'])->name('voting.realized');

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        //Stripe
        Route::post('payment',[StripeController::class, 'store'])->name('stripe.payment');

        Route::post('/voting/select-tariff', [VotingController::class, 'selectTariff'])->name('voting.select_tariff');

        // Mark booking as completed
        Route::post('/voting/complete', [VotingController::class, 'complete'])
            ->name('voting.complete');

        Route::get('/terms', function () {
                return view('term-condition.terms-for-tariff-selection'); // resources/views/terms.blade.php
            })->name('terms.show');
        
        Route::post('incompleteVotingForm/{id}', [VotingController::class, 'incompleteVotingForm'])->name('incomplete.voting.form');
        Route::get('/t&cTariffSelection',[TermConditionsController::class, 'tariffSelection'])->name('t&cTariffSelection');
        
        // CSV File For Voter Email
        Route::get('/export-voting-event-emails/{id}', [VotingController::class, 'exportVotingEventEmails'])->name('voting.event.emails');
        
    });

    Route::middleware(['auth', 'verified','role:1'])->group(function () {
        
        Route::get('voter.history',[DashboardController::class, 'voterHistory'])->name('voterHistory');

        Route::post('/voting/{token}/submit', [VotingController::class, 'submitVote'])
            ->name('voting.submit');

        // PRG success page after submitting a vote
        Route::get('/voting/{token}/success', [VotingController::class, 'voteSuccess'])
            ->name('voting.success');
    });

    Route::get('/voting/{token}', [VotingController::class, 'publicVoting'])
            ->name('voting.public');
    //Route::get('/votingSignIn/{token}', [VotingController::class, 'votingSignIn'])->name('votingSignIn');
    Route::get('/storage-link', function () {
        try {
            Artisan::call('storage:link');
            return 'Storage link created successfully!';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    });
});
 
// admin side routes

Route::prefix('admin')->group(function () {
    Route::get('/', function() {
        return redirect()->route('admin.login');
    });

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');

    Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('register', [RegisterController::class, 'register'])->name('admin.register.submit');


    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        Route::get('dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('users', [UserController::class, 'getUsers'])->name('admin.user');
        Route::post('/admin/user/delete', [UserController::class, 'deleteUser'])->name('admin.user.delete');

        // get creator
        Route::get('/creators', [CreaterController::class, 'index'])->name('admin.creators.index');
        Route::get('/creators/booking/{id}', [CreaterController::class, 'showBookingPoll'])->name('admin.creators.booking.show');

        Route::get('/voters', [VoterController::class, 'index'])->name('admin.voters.index');
        Route::get('/voters/{id}/history', [VoterController::class, 'voterHistory'])->name('admin.voters.history');

        Route::get('/edit-profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::post('/edit-profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

        // add Tariff
        Route::get('/add-tariff', [TariffController::class, 'create'])->name('admin.tariff.create');
        Route::post('/add-tariff', [TariffController::class, 'store'])->name('admin.tariff.store');
        // get Tariff
        Route::get('/tariffs', [TariffController::class, 'index'])->name('admin.tariff.index');
        Route::get('/tariffs/edit/{id}', [TariffController::class, 'edit'])->name('admin.tariff.edit');
        Route::post('/tariffs/update/{id}', [TariffController::class, 'update'])->name('admin.tariff.update');
        Route::delete('/tariffs/delete/{id}', [TariffController::class, 'destroy'])->name('admin.tariff.destroy');
    });
});



