@extends('admin.layout.master')
@section('title')
    Poll Details
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="header-title mb-3 mt-3">Poll Details</h4> --}}
                    @php
                        $now = \Carbon\Carbon::now();
                        $end = \Carbon\Carbon::parse($booking->votingEvent->end_at);

                        $isActive = $now->lte($end);
                    @endphp

                    <div class="d-flex align-items-center position-relative mb-3 mt-3">
                        <!-- Left side: Poll Details -->
                        <h4 class="mb-0">Poll Details</h4>

                        <!-- Center: Active/Expired -->
                        <span class="position-absolute start-50 translate-middle-x"
                            style="color: {{ $isActive ? 'green' : 'red' }}; font-size: 20px;">
                            ({{ $isActive ? 'Active' : 'Expired' }})
                        </span>
                    </div>



                    <p><strong>Start Date:</strong> {{ $booking->votingEvent->start_at->format('d M, Y H:i') }}</p>
                    <p><strong>End Date:</strong> {{ $booking->votingEvent->end_at->format('d M, Y H:i') }}</p>

                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Creator:</strong> {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                            </p>
                            <p><strong>Poll Question:</strong> {{ $booking->votingEvent->question }}</p>

                            @if ($booking->votingEvent->options->count() > 0)
                                <p><strong>Options:</strong></p>
                                <ul>
                                    @foreach ($booking->votingEvent->options as $option)
                                        <li>{{ $option->option_text }} ({{ $option->votes->count() ?? 0 }} votes)</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No options available.</p>
                            @endif
                        </div>

                        <div class="col-md-4 text-end">
                            @php
                                $votingEvent = $booking->votingEvent;
                                $publicUrl = route('voting.public', ['token' => $votingEvent->token]);
                            @endphp

                            @if ($votingEvent && $booking->booking_status === 'Completed')
                                <h5>Poll QR Code</h5>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($publicUrl) }}"
                                    alt="QR Code" class="img-thumbnail" style="width:200px;">
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h5 class="mt-4">All Votes</h5>

                    @if ($paginatedVotes->count() > 0)
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>Voting Event ID</th>
                                    <th>Voting Option</th>
                                    <th>Voter Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paginatedVotes as $vote)
                                    <tr>
                                        <td>{{ $vote->voting_event_id }}</td>
                                        <td>{{ $vote->option_text }}</td>
                                        <td>{{ $vote->email ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $paginatedVotes->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <p>No votes have been cast yet.</p>
                    @endif

                    <a href="{{ route('admin.creators.index') }}" class="btn btn-secondary mt-3">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
