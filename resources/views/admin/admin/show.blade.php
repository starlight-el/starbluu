@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4">Detail Admin</h3>

<table class="table table-bordered" style="max-width: 600px;">
    <tr>
        <th style="width: 150px;">ID</th>
        <td>{{ $admin->id }}</td>
    </tr>
    <tr>
        <th>Nama</th>
        <td>{{ $admin->name }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $admin->email }}</td>
    </tr>
    <tr>
        <th>Dibuat Pada</th>
        <td>{{ $admin->created_at->format('d M Y, H:i') }}</td>
    </tr>
</table>

<div>
    <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">Kembali</a>
    <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-dark">Edit</a>
    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data Admin ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Hapus</button>
    </form>
</div>
@endsection