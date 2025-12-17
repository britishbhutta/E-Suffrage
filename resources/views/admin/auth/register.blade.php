<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <!-- MDB CSS (optional, you can use CDN) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet"/>
    <!-- Font Awesome for social icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Admin Registration</h3>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/admin/register') }}">
            @csrf

            <!-- First Name -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" name="first_name" id="firstName" class="form-control" required />
                <label class="form-label" for="firstName">First Name</label>
            </div>

            <!-- Last Name -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" name="last_name" id="lastName" class="form-control" required />
                <label class="form-label" for="lastName">Last Name</label>
            </div>

            <!-- Email -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" name="email" id="email" class="form-control" required />
                <label class="form-label" for="email">Email Address</label>
            </div>

            <!-- Password -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" name="password" id="password" class="form-control" required />
                <label class="form-label" for="password">Password</label>
            </div>

            <!-- Confirm Password -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" name="password_confirmation" id="confirmPassword" class="form-control" required />
                <label class="form-label" for="confirmPassword">Confirm Password</label>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">Register</button>

            <!-- Link to login -->
            <div class="text-center">
                <p>Already registered? <a href="{{ route('admin.login') }}">Sign in</a></p>
            </div>
        </form>
    </div>
</div>
<!-- MDB JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>
</html>
