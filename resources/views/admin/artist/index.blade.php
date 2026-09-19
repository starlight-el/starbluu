@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Kelola Data Artist</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Manajemen data artist, foto, dan member Starbluu.</p>
            </div>
            <a href="{{ route('admin.artists.create') }}" class="btn btn-dark">+ TAMBAH DATA</a>
        </div>

        <div class="admin-card mt-4">
            <table class="table align-middle mb-0 admin-table">
                <colgroup>
                    <col style="width: 32%;">
                    <col style="width: 20%;">
                    <col style="width: 20%;">
                    <col style="width: 28%;">
                </colgroup>
                <thead>
                    <tr>
                        <th>Nama Grup</th>
                        <th>Foto</th>
                        <th class="text-center">Jumlah Member</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($artists as $artist)
                        <tr>
                            <td class="fw-bold text-white">{{ $artist->nama_grup }}</td>
                            <td>
                                @if ($artist->foto_thumbnail)
                                    <img src="{{ Storage::url($artist->foto_thumbnail) }}" alt="{{ $artist->nama_grup }}" class="admin-photo-thumb">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $artist->artist_members_count }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.artists.edit', $artist->id) }}" class="admin-action-btn btn-edit">Edit</a>
                                    <form action="{{ route('admin.artists.destroy', $artist->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data Artist ini beserta seluruh member-nya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-btn btn-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <div class="mb-2" style="font-size: 2rem;">🎤</div>
                                Belum ada data Artist.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection