@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Kelola Data Admin</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Manajemen akses dan akun administrator Starbluu.</p>
            </div>
            <a href="{{ route('admin.admins.create') }}" class="btn btn-dark">+ TAMBAH DATA</a>
        </div>

        <div class="admin-card mt-4">
            <table class="table align-middle mb-0 admin-table">
                <colgroup>
                    <col style="width: 36%;">
                    <col style="width: 42%;">
                    <col style="width: 22%;">
                </colgroup>
                <thead>
                    <tr>
                        <th style="padding-left: 68px;">Nama</th>
                        <th>Email</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="admin-initial-avatar">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white mb-1">{{ $admin->name }}</div>
                                        <span class="badge rounded-pill" style="background-color: rgba(76, 141, 255, 0.15); color: #4C8DFF; font-weight: 500; font-size: 0.7rem;">Administrator</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $admin->email }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.admins.show', $admin->id) }}" class="admin-action-btn btn-detail">Detail</a>
                                    <a href="{{ route('admin.admins.edit', $admin->id) }}" class="admin-action-btn btn-edit">Edit</a>
                                    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data Admin ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-btn btn-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <div class="mb-2" style="font-size: 2rem;">👤</div>
                                Belum ada data Admin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection