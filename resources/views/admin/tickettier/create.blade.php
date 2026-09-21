@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Tambah Data Ticket Tier</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Pilih Jadwal yang belum punya tier, lalu isi tier-nya (bisa lebih dari 1 sekaligus).</p>
            </div>
        </div>

        @php
            $oldTiers = old('tiers');
            if (empty($oldTiers)) {
                $oldTiers = [['nama_tier' => '', 'harga' => '', 'kuota' => '']];
            }
        @endphp

        <div class="admin-form-card mt-4">
            <form action="{{ route('admin.tickettiers.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label text-muted">Pilih Jadwal Tour</label>
                    <select name="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach ($jadwals as $jadwal)
                            <option value="{{ $jadwal->id }}" {{ (old('jadwal_id', $selectedJadwalId)) == $jadwal->id ? 'selected' : '' }}>
                                {{ $jadwal->tour->artist->nama_grup }} - {{ $jadwal->kota }} {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr style="border-color: rgba(255,255,255,0.08); margin: 32px 0;">

                <h6 class="fw-bold text-white mb-3" style="font-size: 0.95rem;">Tier</h6>
                <div id="tier-list">
                    @foreach ($oldTiers as $index => $tier)
                        <div class="tier-row">
                            <select name="tiers[{{ $index }}][nama_tier]" class="form-select tier-select" required>
                                <option value="">-- Nama Tier --</option>
                                @foreach (['VIP Soundcheck', 'Floor/Standing', 'CAT 1', 'CAT 2', 'CAT 3'] as $opsi)
                                    <option value="{{ $opsi }}" {{ ($tier['nama_tier'] ?? '') === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="tiers[{{ $index }}][harga]" class="form-control tier-price" value="{{ $tier['harga'] ?? '' }}" placeholder="Harga (Rp)" min="0" required>
                            <input type="number" name="tiers[{{ $index }}][kuota]" class="form-control tier-quota" value="{{ $tier['kuota'] ?? '' }}" placeholder="Kuota" min="0" required>
                            <button type="button" class="tier-remove-btn btn-remove-tier" aria-label="Hapus tier"></button>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="btn-add-tier" class="admin-btn-success mb-4" style="padding: 8px 20px; font-size: 0.75rem; margin-top: 4px;">+ Tambah Tier</button>

                <div class="admin-form-actions pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                    <button type="submit" class="admin-btn-solid">Simpan</button>
                    <a href="{{ route('admin.tickettiers.index') }}" class="admin-btn-outline">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
<script>
let tierIndex = {{ count($oldTiers) }};

document.getElementById('btn-add-tier').addEventListener('click', function () {
    const container = document.getElementById('tier-list');

    const row = document.createElement('div');
    row.className = 'tier-row';
    row.innerHTML = `
        <select name="tiers[${tierIndex}][nama_tier]" class="form-select tier-select" required>
            <option value="">-- Nama Tier --</option>
            <option value="VIP Soundcheck">VIP Soundcheck</option>
            <option value="Floor/Standing">Floor/Standing</option>
            <option value="CAT 1">CAT 1</option>
            <option value="CAT 2">CAT 2</option>
            <option value="CAT 3">CAT 3</option>
        </select>
        <input type="number" name="tiers[${tierIndex}][harga]" class="form-control tier-price" placeholder="Harga (Rp)" min="0" required>
        <input type="number" name="tiers[${tierIndex}][kuota]" class="form-control tier-quota" placeholder="Kuota" min="0" required>
        <button type="button" class="tier-remove-btn btn-remove-tier" aria-label="Hapus tier"></button>
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
            Swal.fire({
                icon: 'warning',
                title: 'Tidak bisa dihapus',
                text: 'Minimal harus ada 1 tier.',
                buttonsStyling: false,
                customClass: {
                    popup: 'bluu-swal-popup',
                    title: 'bluu-swal-title',
                    htmlContainer: 'bluu-swal-text',
                    confirmButton: 'bluu-swal-confirm',
                },
            });
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
                buttonsStyling: false,
                customClass: {
                    popup: 'bluu-swal-popup',
                    title: 'bluu-swal-title',
                    htmlContainer: 'bluu-swal-text',
                    confirmButton: 'bluu-swal-confirm',
                },
            });
        });
    </script>
@endif
@endpush