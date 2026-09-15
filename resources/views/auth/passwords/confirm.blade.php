@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">
    <div class="auth-plain-wrap">
        <h3 class="auth-title">Confirm Password</h3>
        <p class="auth-subtitle">{{ __('Please confirm your password before continuing.') }}</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn-accent-solid">
                {{ __('Confirm Password') }}
            </button>

            @if (Route::has('password.request'))
                <p class="auth-switch">
                    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                </p>
            @endif
        </form>
    </div>
</div>
@endsection
