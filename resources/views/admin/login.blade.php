<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ $settings->app_name ?? 'Alfa Mobiles' }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $settings->app_icon ?? asset('asset/image/01_app_icon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0e14;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            background: #161b22;
            border: 1px solid #30363d;
            text-align: center;
        }
        .login-logo {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            margin-bottom: 20px;
            object-fit: cover;
            border: 1px solid #30363d;
        }
        .form-control {
            background-color: #0d1117;
            border: 1px solid #30363d;
            color: #fff;
            padding: 12px;
        }
        .form-control:focus {
            background-color: #0d1117;
            border-color: #007bff;
            color: #fff;
            box-shadow: none;
        }
        .form-label {
            color: #8b949e;
            display: block;
            text-align: left;
        }
        h3 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    @php
        $settings = \Illuminate\Support\Facades\DB::table('app_settings')->where('id', 1)->first();
    @endphp
    <div class="login-card">
        <img src="{{ $settings->app_icon ?? asset('asset/image/01_app_icon.png') }}" alt="Logo" class="login-logo">
        <h3>Admin Login</h3>

        @if($errors->any())
            <div class="alert alert-danger bg-danger text-white border-0 py-2 small mb-4">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="password" class="form-label">Administrator Password</label>
                <input type="password" name="password" id="password" class="form-control" required autofocus placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary w-100">Access Dashboard</button>
        </form>
    </div>
</body>
</html>
