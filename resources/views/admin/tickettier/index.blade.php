@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Kelola Data Ticket Tier</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Manajemen tier tiket untuk setiap jadwal konser Starbluu.</p>
            </div>
            <div class="admin-header-actions">
                <form method="GET" action="{{ route('admin.tickettiers.index') }}" class="m-0">
                    <select name="artist" class="form-select tier-filter-select" onchange="this.form.submit()">
                        <option value="">Semua Artist</option>
                        @foreach ($artists as $artist)
                            <option value="{{ $artist->id }}" {{ (string) $artistId === (string) $artist->id ? 'selected' : '' }}>{{ $artist->nama_grup }}</option>
                        @endforeach
                    </select>
                </form>
                
                <a href="{{ route('admin.tickettiers.create') }}" class="btn btn-dark">+ TAMBAH DATA</a>
            </div>
        </div>

        <div class="admin-card mt-4">
            <table class="table align-middle mb-0 admin-table">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 20%;">
                    <col style="width: 30%;">
                </colgroup>
                <thead>
                    <tr>
                        <th>Jadwal Terkait</th>
                        <th class="text-center">Jumlah Tier</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $jadwal)
                        <tr>
                            <td class="fw-bold text-white">
                                {{ $jadwal->tour->artist->nama_grup }} - {{ $jadwal->kota }} {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}
                            </td>
                            <td class="text-center">
                                <span class="text-muted">{{ $jadwal->ticket_tiers_count }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    @if ($jadwal->ticket_tiers_count > 0)
                                        <a href="{{ route('admin.tickettiers.edit', $jadwal->id) }}" class="admin-action-btn btn-edit">Edit</a>
                                        <form action="{{ route('admin.tickettiers.destroy', $jadwal->id) }}" method="POST" class="d-inline" data-confirm="Yakin mau hapus SEMUA Ticket Tier untuk jadwal ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action-btn btn-delete">Hapus</button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.tickettiers.create', ['jadwal_id' => $jadwal->id]) }}" class="admin-action-btn btn-add">Tambah</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <div class="mb-2" style="font-size: 2rem;">🎟️</div>
                                Belum ada data Jadwal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($jadwals->hasPages())
                <div class="admin-pagination-wrap">
                    {{ $jadwals->links('pagination.starbluu') }}
                </div>
            @endif
        </div>

    </div>
@endsection