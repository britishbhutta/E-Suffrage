@extends('admin.layout.master')

@section('title', 'Voter History')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mt-4">
                        Voting History of {{ $voter->first_name }} {{ $voter->last_name }}
                    </h4>

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($votingEventVotes as $vote)
                                <tr>
                                    <td>{{ $vote->votingEvent->question ?? 'N/A' }}</td>
                                    <td>{{ $vote->option->option_text ?? 'N/A' }}</td>
                                    <td>{{ $vote->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $votingEventVotes->links('pagination::bootstrap-5') }}
                    </div>
                    <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary mt-3">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
