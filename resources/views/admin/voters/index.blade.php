@extends('admin.layout.master')
@section('title')
    Voters List
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-4">Voters List</h4>

                    <table data-toggle="table" data-show-columns="false" data-page-list="[5, 10, 20]" data-page-size="5"
                        data-buttons-class="xs btn-light" data-pagination="true"
                        class="table table-bordered table-hover table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th>Voters</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($voters as $voter)
                                <tr>
                                    <td>{{ $voter->first_name }} {{ $voter->last_name }}</td>
                                    <td>{{ $voter->email }}</td>
                                    <td>
                                        <a href="{{ route('admin.voters.history', $voter->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
