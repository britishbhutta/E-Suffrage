@extends('admin.layout.master')
@section('title')
    Activity Logs
@endsection

@section('content')
<main class="content-page">
    <div class="content">
        <div class="container-fluid">

            <div class="row py-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Activity Logs</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-light">
                                    <thead style="background-color: #f2f2f2;">
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Action</th>
                                            <th>Module</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="word-wrap: break-word; max-width: 150px;">
                                                   {{ $log->users ? $log->users->first_name . ' ' . $log->users->last_name : 'N/A' }}
                                                </td>
                                                <td style="word-wrap: break-word; max-width: 120px;">{{ $log->action }}</td>
                                                <td style="word-wrap: break-word; max-width: 120px;">{{ $log->module }}</td>
                                                <td style="word-wrap: break-word; max-width: 250px;">{{ $log->description }}</td>
                                                <td>{{ $log->created_at->format('d M Y h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    No activity logs found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center">
                                    {{ $logs->links('pagination::bootstrap-5') }}
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
