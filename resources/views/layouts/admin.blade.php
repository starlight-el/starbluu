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
<body class="starbluu-admin">

    <div class="admin-topbar">
        <span class="admin-brand">star<span>bluu</span></span>

        <div class="dropdown">
            <a href="#" class="admin-user-toggle dropdown-toggle" data-bs-toggle="dropdown">
                <span class="admin-user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">Profile</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="admin-body">
        <div class="admin-sidebar">
            <ul class="admin-nav">
                <li class="admin-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.tours.index') }}" class="admin-nav-link {{ request()->routeIs('admin.tours.*') ? 'active' : '' }}">Tour & Jadwal</a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.artists.index') }}" class="admin-nav-link {{ request()->routeIs('admin.artists.*') ? 'active' : '' }}">Artist</a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.tickettiers.index') }}" class="admin-nav-link {{ request()->routeIs('admin.tickettiers.*') ? 'active' : '' }}">Ticket Tier</a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.checkin.index') }}" class="admin-nav-link {{ request()->routeIs('admin.checkin.*') ? 'active' : '' }}">Validasi Check-In</a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.admins.index') }}" class="admin-nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">Admin</a>
                </li>
            </ul>
        </div>

        <div class="admin-content">
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

    @if (session('info'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{!! session('info') !!}',
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'bluu-swal-popup',
                    title: 'bluu-swal-title',
                    htmlContainer: 'bluu-swal-text',
                },
            });
        </script>
    @endif

</body>
</html>