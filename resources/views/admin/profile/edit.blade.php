@extends('admin.layout.master')

@section('title', 'Edit Profile')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-4 mt-4">Edit Profile</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control"
                               value="{{ old('first_name', $user->first_name) }}">
                        @error('first_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control"
                               value="{{ old('last_name', $user->last_name) }}">
                        @error('last_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Email (Readonly)</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>New Password (Optional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter new password">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        Update Profile
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
