<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Admin - starbluu</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito|Unbounded:400,700" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #0A0A0C;
            margin: 0;
            font-family: 'Plus Jakarta Sans', 'Nunito', sans-serif;
        }

        .admin-login-viewport {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: radial-gradient(circle at top right, rgba(76, 141, 255, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(76, 141, 255, 0.05), transparent 40%);
        }

        .admin-login-card {
            width: 100%;
            max-width: 420px;
            background-color: rgba(24, 25, 29, 0.45);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .login-brand {
            font-family: 'Unbounded', sans-serif;
            font-size: 1.6rem;
            color: #F5F5F7;
            text-align: center;
            margin-bottom: 32px;
        }

        .login-brand span {
            color: #4C8DFF;
        }

        .form-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 6px;
            color: #9A9BA3;
        }

        .btn-login-solid {
            width: 100%;
            background-color: #4C8DFF;
            color: #0A0A0C;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            padding: 12px;
            border: none;
            border-radius: 8px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-login-solid:hover {
            background-color: #3a76df;
            color: #fff;
            box-shadow: 0 4px 15px rgba(76, 141, 255, 0.3);
        }
    </style>
</head>
<body class="starbluu-theme">

    <div class="admin-login-viewport">
        <div class="admin-login-card">
            
            <div class="login-brand">
                star<span>bluu</span>
            </div>

            <div class="mb-4 text-center">
                <h3 class="auth-title text-white" style="margin-bottom: 4px; font-size: 1.2rem; font-weight: 600;">Login Admin</h3>
                <!-- Warna dibikin spesifik #9A9BA3 biar terang dan kebaca -->
                <p class="auth-subtitle" style="font-size: 0.85rem; color: #9A9BA3;">Masuk untuk mengelola aplikasi Starbluu.</p>
            </div>

            <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="off">
                    @error('email')
                        <span class="invalid-feedback" role="alert" style="font-size: 0.75rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="off">
                    @error('password')
                        <span class="invalid-feedback" role="alert" style="font-size: 0.75rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn-login-solid">LOGIN</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{!! $errors->first() !!}',
                buttonsStyling: false,
                customClass: {
                    popup: 'bluu-swal-popup',
                    title: 'bluu-swal-title',
                    htmlContainer: 'bluu-swal-text',
                    confirmButton: 'bluu-swal-confirm',
                },
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{!! session('error') !!}',
                buttonsStyling: false,
                customClass: {
                    popup: 'bluu-swal-popup',
                    title: 'bluu-swal-title',
                    htmlContainer: 'bluu-swal-text',
                    confirmButton: 'bluu-swal-confirm',
                },
            });
        </script>
    @endif

</body>
</html>