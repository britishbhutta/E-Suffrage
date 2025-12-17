<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VotingEventVote;

class VoterController extends Controller
{
     public function index()
    {
        $voters = User::where('role', 1)
            ->get();
        return view('admin.voters.index', compact('voters'));
    }

    public function voterHistory($id)
{
    $voter = User::findOrFail($id);
     $votingEventVotes = VotingEventVote::where('email', $voter->email)
        ->orderBy('created_at', 'desc')
        ->paginate(5);

    return view('admin.voters.history', compact('votingEventVotes', 'voter'));
}

}
