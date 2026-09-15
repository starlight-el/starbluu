@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">
    <div class="auth-split">
        
        <div class="auth-form-panel" style="padding: 0 40px;">
            <div class="auth-form-box">
                <h3 class="auth-title" style="margin-bottom: 0; font-size: 1.1rem;">Register</h3>
                <p class="auth-subtitle" style="margin-bottom: 8px; font-size: 0.7rem;">Buat akun buat mulai pesan tiket konser favoritmu.</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-1">
                        <label for="name" class="form-label" style="margin-bottom: 0; font-size: 0.65rem;">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus style="padding: 2px 10px; font-size: 0.75rem; height: 30px;">
                        
                        @error('name')
                            <span class="invalid-feedback" role="alert" style="font-size: 0.65rem;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="email" class="form-label" style="margin-bottom: 0; font-size: 0.65rem;">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="padding: 2px 10px; font-size: 0.75rem; height: 30px;">
                        
                        @error('email')
                            <span class="invalid-feedback" role="alert" style="font-size: 0.65rem;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="password" class="form-label" style="margin-bottom: 0; font-size: 0.65rem;">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" style="padding: 2px 10px; font-size: 0.75rem; height: 30px;">
                        
                        @error('password')
                            <span class="invalid-feedback" role="alert" style="font-size: 0.65rem;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="password-confirm" class="form-label" style="margin-bottom: 0; font-size: 0.65rem;">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" style="padding: 2px 10px; font-size: 0.75rem; height: 30px;">
                    </div>

                    <button type="submit" class="btn-accent-solid" style="padding: 6px 22px; font-size: 0.75rem;">
                        {{ __('Register') }}
                    </button>
                </form>

                <p class="auth-switch mb-0" style="margin-top: 8px; font-size: 0.7rem;">{{ __('Sudah punya akun?') }} <a href="{{ route('login') }}">{{ __('Login di sini') }}</a></p>
            </div>
        </div>

        <div class="auth-side-panel">
            <div class="auth-marquee" id="auth-marquee">
                <div class="auth-marquee-track">
                    @foreach ($tours as $tour)
                        <div class="auth-marquee-item" style="background-image: url('{{ asset('storage/' . $tour->foto_banner_detail) }}');"></div>
                    @endforeach
                    @foreach ($tours as $tour)
                        <div class="auth-marquee-item" style="background-image: url('{{ asset('storage/' . $tour->foto_banner_detail) }}');"></div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const marquee = document.getElementById('auth-marquee');
    if (!marquee) return;

    let isPaused = false;
    let resumeTimeout;

    function autoScroll() {
        if (!isPaused) {
            marquee.scrollTop += 0.5;
            if (marquee.scrollTop >= marquee.scrollHeight / 2) {
                marquee.scrollTop = 0;
            }
        }
        requestAnimationFrame(autoScroll);
    }

    function pauseScroll() {
        isPaused = true;
        clearTimeout(resumeTimeout);
    }

    function resumeScrollLater() {
        clearTimeout(resumeTimeout);
        resumeTimeout = setTimeout(function () {
            isPaused = false;
        }, 2000);
    }

    marquee.addEventListener('mouseenter', pauseScroll);
    marquee.addEventListener('mouseleave', resumeScrollLater);
    marquee.addEventListener('touchstart', pauseScroll, { passive: true });
    marquee.addEventListener('touchend', resumeScrollLater);
    marquee.addEventListener('wheel', function () {
        pauseScroll();
        resumeScrollLater();
    }, { passive: true });

    requestAnimationFrame(autoScroll);
});
</script>
@endpush