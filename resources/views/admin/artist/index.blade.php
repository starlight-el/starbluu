@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Data Artist</h3>
    <a href="{{ route('admin.artists.create') }}" class="btn btn-dark">+ TAMBAH DATA</a>
</div>

<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>Nama Grup</th>
            <th>Foto</th>
            <th>Jumlah Member</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($artists as $artist)
            <tr>
                <td>{{ $artist->nama_grup }}</td>
                <td>
                    @if ($artist->foto_thumbnail)
                        <img src="{{ Storage::url($artist->foto_thumbnail) }}" alt="{{ $artist->nama_grup }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $artist->artist_members_count }}</td>
                <td>
                    <a href="{{ route('admin.artists.edit', $artist->id) }}">Edit</a>
                    |
                    <form action="{{ route('admin.artists.destroy', $artist->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data Artist ini beserta seluruh member-nya?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 text-danger" style="text-decoration: none;">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Belum ada data Artist.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection