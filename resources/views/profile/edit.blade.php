@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">
    <div class="auth-plain-wrap">
        <div style="width: 100%; max-width: 360px; margin: 0 auto;">
            <h3 class="auth-title" style="margin-bottom: 0; font-size: 1.1rem;">Profil Saya</h3>
            <p class="auth-subtitle" style="margin-bottom: 8px; font-size: 0.7rem;">Kelola data akun kamu di sini.</p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-1">
                    <label class="form-label" style="margin-bottom: 0; font-size: 0.65rem;">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" style="padding: 2px 10px; font-size: 0.75rem; height: 30px;">
                </div>

                <div class="mb-1">
                    <label class="form-label" style="margin-bottom: 0; font-size: 0.65rem;">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" style="padding: 2px 10px; font-size: 0.75rem; height: 30px;">
                </div>

                <div class="mb-1">
                    <label class="form-label" style="margin-bottom: 0; font-size: 0.65rem;">Password Baru <span class="helper" style="font-size: 0.6rem; text-transform: none; letter-spacing: normal;">(kosongkan jika tidak ingin ganti)</span></label>
                    <input type="password" name="password" class="form-control" style="padding: 2px 10px; font-size: 0.75rem; height: 30px;">
                </div>

                <div class="mb-2">
                    <label class="form-label" style="margin-bottom: 0; font-size: 0.65rem;">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" style="padding: 2px 10px; font-size: 0.75rem; height: 30px;">
                </div>

                <div class="checkout-actions" style="margin-bottom: 0; gap: 8px; margin-top: 16px;">
                    <a href="{{ route('landing') }}" class="btn-neutral-outline" style="padding: 6px 0; font-size: 0.75rem; white-space: nowrap;">Batal</a>
                    <button type="submit" class="btn-accent-solid" style="padding: 6px 0; font-size: 0.75rem; white-space: nowrap;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{!! $errors->first() !!}',
                    confirmButtonColor: '#212529',
                });
            });
        </script>
    @endif
@endpush