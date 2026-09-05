@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-1">Tambah Data Ticket Tier</h3>
<p class="text-muted mb-4">Pilih Jadwal yang belum punya tier, lalu isi tier-nya (bisa lebih dari 1 sekaligus).</p>

@php
    $oldTiers = old('tiers');
    if (!$oldTiers) {
        $oldTiers = [['nama_tier' => '', 'harga' => '', 'kuota' => '']];
    }
@endphp

<form action="{{ route('admin.tickettiers.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label text-muted">Pilih Jadwal Tour</label>
        <select name="jadwal_id" class="form-select" required>
            <option value="">-- Pilih Jadwal --</option>
            @foreach ($jadwals as $jadwal)
                <option value="{{ $jadwal->id }}" {{ (old('jadwal_id', $selectedJadwalId)) == $jadwal->id ? 'selected' : '' }}>
                    {{ $jadwal->tour->artist->nama_grup }} - {{ $jadwal->kota }} {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}
                </option>
            @endforeach
        </select>
    </div>

    <label class="form-label fw-bold">Tier</label>
    <div id="tier-list">
        @foreach ($oldTiers as $index => $tier)
            <div class="tier-row border rounded p-3 mb-2">
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="tiers[{{ $index }}][nama_tier]" class="form-select" required>
                            <option value="">-- Nama Tier --</option>
                            @foreach (['VIP Soundcheck', 'Floor/Standing', 'CAT 1', 'CAT 2', 'CAT 3'] as $opsi)
                                <option value="{{ $opsi }}" {{ ($tier['nama_tier'] ?? '') === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="tiers[{{ $index }}][harga]" class="form-control" value="{{ $tier['harga'] ?? '' }}" placeholder="Harga" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="tiers[{{ $index }}][kuota]" class="form-control" value="{{ $tier['kuota'] ?? '' }}" placeholder="Kuota" min="0" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-outline-danger btn-remove-tier">x</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <button type="button" id="btn-add-tier" class="btn btn-outline-dark btn-sm mb-4">+ TAMBAH TIER</button>

    <div>
        <button type="submit" class="btn btn-dark">SIMPAN</button>
        <a href="{{ route('admin.tickettiers.index') }}" class="btn btn-outline-secondary">BATAL</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
let tierIndex = {{ count($oldTiers) }};

document.getElementById('btn-add-tier').addEventListener('click', function () {
    const container = document.getElementById('tier-list');

    const row = document.createElement('div');
    row.className = 'tier-row border rounded p-3 mb-2';
    row.innerHTML = `
        <div class="row g-2">
            <div class="col-md-4">
                <select name="tiers[${tierIndex}][nama_tier]" class="form-select" required>
                    <option value="">-- Nama Tier --</option>
                    <option value="VIP Soundcheck">VIP Soundcheck</option>
                    <option value="Floor/Standing">Floor/Standing</option>
                    <option value="CAT 1">CAT 1</option>
                    <option value="CAT 2">CAT 2</option>
                    <option value="CAT 3">CAT 3</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="tiers[${tierIndex}][harga]" class="form-control" placeholder="Harga" min="0" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="tiers[${tierIndex}][kuota]" class="form-control" placeholder="Kuota" min="0" required>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger btn-remove-tier">x</button>
            </div>
        </div>
    `;

    container.appendChild(row);
    tierIndex++;
});

document.getElementById('tier-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-tier')) {
        const rows = document.querySelectorAll('.tier-row');

        if (rows.length > 1) {
            e.target.closest('.tier-row').remove();
        } else {
            alert('Minimal harus ada 1 tier.');
        }
    }
});
</script>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{!! $errors->first() !!}',
                confirmButtonColor: '#212529',
            });
        });
    </script>
@endif
@endpush