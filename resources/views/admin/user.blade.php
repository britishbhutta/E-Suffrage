@extends('admin.layout.master')
@section('title')
    User Detail
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-4">User Detail</h4>

                    <table data-toggle="table" data-show-columns="false" data-page-list="[5, 10, 20]" data-page-size="5"
                        data-buttons-class="xs btn-light" data-pagination="true"
                        class="table table-bordered table-hover table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->first_name }}</td>
                                    <td>{{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role == 1 ? 'Voter' : ($user->role == 2 ? 'Creator' : 'Unknown') }}</td>

                                    <td>
                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $user->id }}"
                                            data-name="{{ $user->first_name }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- model popup reason --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="deleteForm" method="POST" action="{{ route('admin.user.delete') }}">
            @csrf
            <input type="hidden" name="user_id" id="deleteUserId">

            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete <b id="userName"></b>?</p>

                    <label>Reason for deleting:</label>
                    <textarea name="reason" class="form-control" required placeholder="Enter reason"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".deleteBtn").forEach(button => {
            button.addEventListener("click", function () {

                let userId = this.getAttribute("data-id");
                let userName = this.getAttribute("data-name");

                // Set values inside modal
                document.getElementById("deleteUserId").value = userId;
                document.getElementById("userName").innerText = userName;

                // Open modal (Bootstrap 5)
                let modal = new bootstrap.Modal(document.getElementById("deleteModal"));
                modal.show();
            });
        });
    });
</script>


@endsection
