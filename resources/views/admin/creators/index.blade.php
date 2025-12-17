@extends('admin.layout.master')
@section('title')
    Creators List
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-4">Creators List</h4>

                <table data-toggle="table" data-show-columns="false" data-page-list="[5, 10, 20]" data-page-size="5"
                    data-buttons-class="xs btn-light" data-pagination="true"
                    class="table table-bordered table-hover table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th>Creator</th>
                            <th>Poll Question</th>
                            <th>Poll Start Time</th>
                            <th>Poll End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($creators as $creator)
                            @foreach ($creator->booking as $booking)
                                @if($booking->votingEvent)
                                <tr>
                                    <td>{{ $creator->first_name }} {{ $creator->last_name }}</td>
                                    <td>{{ $booking->votingEvent->question }}</td>
                                    <td>{{ $booking->votingEvent->start_at }}</td>
                                    <td>{{ $booking->votingEvent->end_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.creators.booking.show', $booking->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
