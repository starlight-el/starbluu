@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">
    <div class="auth-plain-wrap">
        <h3 class="auth-title">Reset Password</h3>
        <p class="auth-subtitle">Masukkan email kamu, nanti kami kirimkan link buat reset password.</p>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn-accent-solid">
                {{ __('Send Password Reset Link') }}
            </button>
        </form>
    </div>
</div>
@endsection
