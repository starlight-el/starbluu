@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-1">Tambah Data Admin</h3>
<p class="text-muted mb-4">Isi seluruh data baru dari awal, semua kolom masih kosong.</p>

<form action="{{ route('admin.admins.store') }}" method="POST" style="max-width: 600px;">
    @csrf

    <div class="mb-3">
        <label class="form-label text-muted">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-4">
        <label class="form-label text-muted">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div>
        <button type="submit" class="btn btn-dark">SIMPAN</button>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">BATAL</a>
    </div>
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