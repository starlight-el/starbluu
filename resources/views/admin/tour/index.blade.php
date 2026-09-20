@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Kelola Data Tour & Jadwal</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Manajemen data tour, jadwal konser, dan banner Starbluu.</p>
            </div>
            <a href="{{ route('admin.tours.create') }}" class="btn btn-dark">+ TAMBAH DATA</a>
        </div>

        <div class="admin-card mt-4">
            <table class="table align-middle mb-0 admin-table">
                <colgroup>
                    <col style="width: 34%;">
                    <col style="width: 20%;">
                    <col style="width: 16%;">
                    <col style="width: 12%;">
                    <col style="width: 18%;">
                </colgroup>
                <thead>
                    <tr>
                        <th>Nama Tour</th>
                        <th>Artist</th>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah Jadwal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tours as $tour)
                        <tr>
                            <td class="fw-bold text-white">{{ $tour->nama_tour }}</td>
                            <td>
                                <span class="text-muted">{{ $tour->artist->nama_grup }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $tour->kategori === 'tour' ? 'Tour' : 'World Tour' }}</span>
                            </td>
                            <td class="text-center">{{ $tour->jadwals_count }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.tours.edit', $tour->id) }}" class="admin-action-btn btn-edit">Edit</a>
                                    <form action="{{ route('admin.tours.destroy', $tour->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data Tour ini beserta seluruh jadwalnya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-btn btn-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <div class="mb-2" style="font-size: 2rem;">🎫</div>
                                Belum ada data Tour.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection