@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Data Ticket Tier</h3>
    <a href="{{ route('admin.tickettiers.create') }}" class="btn btn-dark">+ TAMBAH DATA</a>
</div>

<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>Jadwal Terkait</th>
            <th>Jumlah Tier</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($jadwals as $jadwal)
            <tr>
                <td>
                    {{ $jadwal->tour->artist->nama_grup }} - {{ $jadwal->kota }}
                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}
                </td>
                <td>{{ $jadwal->ticket_tiers_count }}</td>
                <td>
                    @if ($jadwal->ticket_tiers_count > 0)
                        <a href="{{ route('admin.tickettiers.edit', $jadwal->id) }}">Edit</a>
                        |
                        <form action="{{ route('admin.tickettiers.destroy', $jadwal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus SEMUA Ticket Tier untuk jadwal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger" style="text-decoration: none;">Hapus</button>
                        </form>
                    @else
                        <a href="{{ route('admin.tickettiers.create', ['jadwal_id' => $jadwal->id]) }}">Tambah</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Belum ada data Jadwal.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection