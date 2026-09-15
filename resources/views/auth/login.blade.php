@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">
    <div class="auth-split">
        
        <div class="auth-form-panel" style="padding: 0 40px;">
            <div class="auth-form-box">
                <h3 class="auth-title" style="margin-bottom: 2px; font-size: 1.2rem;">Login</h3>
                <p class="auth-subtitle" style="margin-bottom: 16px; font-size: 0.8rem;">Masuk untuk lanjut pesan tiket konser favoritmu.</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label" style="margin-bottom: 4px; font-size: 0.75rem;">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="padding: 6px 12px; font-size: 0.85rem; height: 36px;">

                        @error('email')
                            <span class="invalid-feedback" role="alert" style="font-size: 0.7rem;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label" style="margin-bottom: 4px; font-size: 0.75rem;">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="padding: 6px 12px; font-size: 0.85rem; height: 36px;">

                        @error('password')
                            <span class="invalid-feedback" role="alert" style="font-size: 0.7rem;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-remember mb-4" style="margin-top: -4px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember" style="font-size: 0.75rem;">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="auth-forgot-link" href="{{ route('password.request') }}" style="font-size: 0.75rem;">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn-accent-solid" style="padding: 10px 22px; font-size: 0.8rem;">
                        {{ __('Login') }}
                    </button>
                </form>

                <p class="auth-switch mb-0" style="margin-top: 16px; font-size: 0.8rem;">{{ __('Belum punya akun?') }} <a href="{{ route('register') }}">{{ __('Register di sini') }}</a></p>
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