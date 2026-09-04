@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Data Tour & Jadwal</h3>
    <a href="{{ route('admin.tours.create') }}" class="btn btn-dark">+ TAMBAH DATA</a>
</div>

<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>Nama Tour</th>
            <th>Artist</th>
            <th>Kategori</th>
            <th>Jumlah Jadwal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tours as $tour)
            <tr>
                <td>{{ $tour->nama_tour }}</td>
                <td>{{ $tour->artist->nama_grup }}</td>
                <td>{{ $tour->kategori === 'tour' ? 'Tour' : 'World Tour' }}</td>
                <td>{{ $tour->jadwals_count }}</td>
                <td>
                    <a href="{{ route('admin.tours.edit', $tour->id) }}">Edit</a>
                    |
                    <form action="{{ route('admin.tours.destroy', $tour->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data Tour ini beserta seluruh jadwalnya?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 text-danger" style="text-decoration: none;">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Belum ada data Tour.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection