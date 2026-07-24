<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editorial Admin Login | Research Journal System</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            padding: 40px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="bg-primary text-white p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
            <i class="bi bi-shield-lock-fill fs-2"></i>
        </div>
        <h4 class="fw-bold text-dark mb-1">Editorial Admin Portal</h4>
        <p class="text-muted small mb-0">Secure Administrator Access Only</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success small mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger small mb-3">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold small">Admin Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@ijaser-journal.org" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small" for="remember">Remember login session</label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
            <i class="bi bi-box-arrow-in-right me-2"></i> Secure Admin Login
        </button>
    </form>

    <div class="text-center mt-4 pt-3 border-top">
        <a href="{{ route('home') }}" class="text-secondary small text-decoration-none">&larr; Back to Public Journal Home</a>
    </div>
</div>

</body>
</html>
