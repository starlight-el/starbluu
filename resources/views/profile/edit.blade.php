@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px;">
    <h3 class="fw-bold mb-4">Profil Saya</h3>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru <span class="text-muted">(kosongkan jika tidak ingin ganti)</span></label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-4">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
        <a href="{{ route('landing') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
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