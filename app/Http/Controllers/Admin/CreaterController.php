<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;

class CreaterController extends Controller
{
     public function index()
    {
        $creators = User::with('booking.votingEvent')
            ->where('role', 2)
            ->get();

        return view('admin.creators.index', compact('creators'));
    }

    // public function showBookingPoll($bookingId)
    // {
    //     $booking = Booking::with('votingEvent.options.votes')->findOrFail($bookingId);
    //     return view('admin.creators.show', compact('booking'));
    // }
   public function showBookingPoll($bookingId)
{
    $booking = Booking::with('votingEvent.options.votes')->findOrFail($bookingId);

    $allVotes = $booking->votingEvent->options->flatMap(function ($option) {
        return $option->votes->map(function ($vote) use ($option) {
            $vote->option_text = $option->option_text; 
            return $vote;
        });
    });

    $perPage = 5;
    $page = request()->get('page', 1);
    $paginatedVotes = new \Illuminate\Pagination\LengthAwarePaginator(
        $allVotes->forPage($page, $perPage),
        $allVotes->count(),
        $perPage,
        $page,
        [
            'path' => request()->url(),
            'query' => request()->query()
        ]
    );

    return view('admin.creators.show', compact('booking', 'paginatedVotes'));
}


}
