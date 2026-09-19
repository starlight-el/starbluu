@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Profil Saya</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Kelola data akun kamu di sini.</p>
            </div>
        </div>

        <div class="admin-form-card mt-4">
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password Baru <span class="text-muted" style="text-transform: none; letter-spacing: normal;">(opsional)</span></label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="admin-form-actions pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                    <button type="submit" class="admin-btn-solid">Simpan Perubahan</button>
                    <a href="{{ url()->previous() }}" class="admin-btn-outline">Batal</a>
                </div>
            </form>
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
                    buttonsStyling: false,
                    customClass: {
                        popup: 'bluu-swal-popup',
                        title: 'bluu-swal-title',
                        htmlContainer: 'bluu-swal-text',
                        confirmButton: 'bluu-swal-confirm',
                    },
                });
            });
        </script>
    @endif
@endpush