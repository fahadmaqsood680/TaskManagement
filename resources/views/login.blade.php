<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laravel App</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

    <div class="card shadow p-4" style="width: 22rem;">
        <h3 class="text-center mb-4 text-primary">Login</h3>
       
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            @if(Session::has('msg'))
            <div class="row p-3">
                <div class="col-md-12">
                    <h5 class="text-danger text-center">{{ Session::get('msg') }}</h5>
                </div>
            </div>
            @endif

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>
