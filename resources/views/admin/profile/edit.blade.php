@extends('layouts.admin')

@section('content')
    <h3 class="fw-bold mb-4">Profil Saya</h3>

    <form method="POST" action="{{ route('admin.profile.update') }}" style="max-width: 600px;">
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
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
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