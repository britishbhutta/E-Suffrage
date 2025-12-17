<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\VotingEventVote;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        
        $setupCompleted = config('app.setup_completed');
        if($setupCompleted === false){
            return redirect('/setup');
        }
        return view('front-end.index');
    }
    public function redirect(){
        
        if (auth()->user()->role == 2) {
            return Booking::where('user_id', auth()->id())->exists()
                ? redirect()->route('voting.realized')
                : redirect()->route('voting.create.step', 1);
        }
        if(auth()->user()->role == 3){
            return Booking::where('user_id', auth()->id())->exists()
                ? redirect()->route('voting.realized')
                : redirect()->route('voting.create.step', 1);
        }
        $token = session('eventToken');
        if ($token) {
            session()->forget('eventToken');
            return redirect()->route('voting.public',[$token]);
        }else{
            return redirect()->route('voterHistory');
        }
        
        
    }

    public function voterHistory(){
        $votingEventVotes = VotingEventVote::where('email', Auth::user()->email)->get();
        return view('voting.voter.index', compact('votingEventVotes'));
    }
}
