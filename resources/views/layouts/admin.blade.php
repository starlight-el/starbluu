<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - starbluu</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

    <nav class="navbar navbar-light bg-white border-bottom px-4">
        <span class="navbar-brand fw-bold mb-0">starbluu</span>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex">
        <div class="border-end bg-light" style="width: 220px; min-height: calc(100vh - 57px);">
            <ul class="nav flex-column p-3">
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link px-0 text-dark {{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link px-0 text-muted">Tour & Jadwal</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.artists.index') }}" class="nav-link px-0 text-dark {{ request()->routeIs('admin.artists.*') ? 'fw-bold' : '' }}">Artist</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link px-0 text-muted">Ticket Tier</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link px-0 text-muted">Validasi Check-In</a>
                </li>
            </ul>
        </div>

        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>

    @stack('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#212529',
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('info') }}',
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
            });
        </script>
    @endif

</body>
</html>