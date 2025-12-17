<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VotingEvent;

class HomeController extends Controller
{
    // public function index()
    // {
    //     $voterCount   = User::where('role', 1)->count();
    //     $creatorCount = User::where('role', 2)->count();
    //     $adminCount   = User::where('role', 3)->count();

    //     $Poll = User::with('booking.votingEvent')->get();

    //     return view('admin.dashboard', compact('voterCount', 'creatorCount', 'adminCount'));
    // }
public function index()
{
    $voterCount   = User::where('role', 1)->count();
    $creatorCount = User::where('role', 2)->count();
    $adminCount   = User::where('role', 3)->count();
    $totalPolls = VotingEvent::count();
    $now = now();
    $runningPolls = VotingEvent::where('start_at', '<=', $now)
                               ->where('end_at', '>=', $now)
                               ->count();
    $completedPolls = VotingEvent::where('end_at', '<', $now)->count();
    return view('admin.dashboard', compact(
        'voterCount',
        'creatorCount',
        'adminCount',
        'totalPolls',
        'runningPolls',
        'completedPolls'
    ));
}


}

