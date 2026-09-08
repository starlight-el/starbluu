@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Data Admin</h3>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-dark">+ TAMBAH DATA</a>
</div>

<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($admins as $admin)
            <tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>
                    <a href="{{ route('admin.admins.show', $admin->id) }}">Detail</a>
                    |
                    <a href="{{ route('admin.admins.edit', $admin->id) }}">Edit</a>
                    |
                    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data Admin ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 text-danger" style="text-decoration: none;">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Belum ada data Admin.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection