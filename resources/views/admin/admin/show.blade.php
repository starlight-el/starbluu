@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Detail Admin</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Informasi akun administrator.</p>
            </div>
        </div>

        <div class="admin-detail-card mt-4">
            <div class="admin-detail-header mb-3">
                <div class="admin-detail-avatar">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
                <div>
                    <div class="admin-detail-name">{{ $admin->name }}</div>
                    <span class="badge rounded-pill" style="background-color: rgba(76, 141, 255, 0.15); color: #4C8DFF; font-weight: 500; font-size: 0.7rem;">Administrator</span>
                </div>
            </div>

            <div class="admin-detail-rows mb-4">
                <div class="admin-detail-row">
                    <span class="admin-detail-label">Email</span>
                    <span class="admin-detail-value">{{ $admin->email }}</span>
                </div>
                <div class="admin-detail-row">
                    <span class="admin-detail-label">Dibuat Pada</span>
                    <span class="admin-detail-value">{{ $admin->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="admin-detail-actions">
                <a href="{{ route('admin.admins.index') }}" class="admin-btn-outline">Kembali</a>
                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="admin-btn-success">Edit</a>
                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data Admin ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger">Hapus</button>
                </form>
            </div>
        </div>

    </div>
@endsection