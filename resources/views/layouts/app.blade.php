<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="@yield('body-class')">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    star<span class="brand-accent">bluu</span>
                </a>

                <div class="navbar-actions">
                    <ul class="navbar-nav navbar-nav-visible mb-0">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    <span class="navbar-username">{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        {{ __('Profile') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav navbar-nav-center">
                        <li class="nav-item">
                            <a href="{{ route('landing') }}" class="nav-link {{ request()->routeIs('landing') ? 'active-nav' : '' }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tour.index') }}" class="nav-link {{ request()->routeIs('tour.index') ? 'active-nav' : '' }}">Tour</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tour.world') }}" class="nav-link {{ request()->routeIs('tour.world') ? 'active-nav' : '' }}">World Tour</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tickets.index') }}" class="nav-link {{ request()->routeIs('tickets.index') ? 'active-nav' : '' }}">My Tickets</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="site-footer">
            <div class="container">
                <div class="footer-top">
                    <div class="footer-brand">
                        <h5>star<span class="brand-accent">bluu</span></h5>
                        <p>Your Front Row to K-pop</p>
                    </div>

                    <div class="footer-social">
                        <a href="#" target="_blank" rel="noopener" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r="1"/>
                            </svg>
                        </a>
                        <a href="#" target="_blank" rel="noopener" aria-label="TikTok">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16.5 3c.3 2 1.8 3.6 3.8 3.9v3a7 7 0 0 1-3.8-1.1v6.4a5.9 5.9 0 1 1-5.9-5.9c.3 0 .6 0 .9.1v3.1a2.9 2.9 0 1 0 2 2.7V3h3z"/>
                            </svg>
                        </a>
                        <a href="#" target="_blank" rel="noopener" aria-label="X">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.9 3H22l-7.2 8.2L23 21h-6.7l-5.3-6.9L4.9 21H2l7.7-8.8L1 3h6.9l4.8 6.4L18.9 3zm-1.2 16h1.9L7.4 5H5.4l12.3 14z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} starbluu. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapseEl = document.getElementById('navbarSupportedContent');
            const profileToggleEl = document.getElementById('navbarDropdown');

            if (!navbarToggler || !navbarCollapseEl) return;

            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(navbarCollapseEl, { toggle: false });

            function closeProfileDropdown() {
                if (!profileToggleEl) return;
                const bsDropdown = bootstrap.Dropdown.getInstance(profileToggleEl);
                if (bsDropdown) bsDropdown.hide();
            }

            navbarToggler.addEventListener('click', closeProfileDropdown);

            if (profileToggleEl) {
                profileToggleEl.addEventListener('click', function () {
                    if (navbarCollapseEl.classList.contains('show')) {
                        bsCollapse.hide();
                    }
                });
            }

            document.addEventListener('click', function (event) {
                const clickedInsideCollapse = navbarCollapseEl.contains(event.target);
                const clickedToggler = navbarToggler.contains(event.target);
                const clickedProfile = profileToggleEl && profileToggleEl.contains(event.target);

                if (navbarCollapseEl.classList.contains('show') && !clickedInsideCollapse && !clickedToggler && !clickedProfile) {
                    bsCollapse.hide();
                }
            });
        });
    </script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{!! session('error') !!}',
                confirmButtonColor: '#212529',
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
            });
        </script>
    @endif
</body>
</html>
