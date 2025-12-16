<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Dashboard RAD-PG</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('client/css/login.css') }}" rel="stylesheet" />

</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo-section">
                <div class="logo">R</div>
                <div class="logo-text">Dashboard RAD-PG</div>
            </div>

            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Enter your credentials to access your account</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="error-message">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Username atau password salah
                    </div>
                @endif

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Masukkan username Anda"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Sign In
                </button>
            </form>
        </div>

        <div class="login-right">
            <div class="illustration">
                <!-- Ganti dengan path logo Anda -->
                <img src="{{ asset('client/img/logo-kabupaten.png') }}" alt="Logo RAD-PG">
            </div>
            <div class="right-content">
                <h2>RENCANA AKSI DAERAH PANGAN DAN GIZI (RAD-PG)</h2>
                {{-- <p >Sistem manajemen monitoring evaluasi yang efisien dan terintegrasi untuk RAD-PG</p> --}}
            </div>
        </div>
    </div>
</body>
</html>
