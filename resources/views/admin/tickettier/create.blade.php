@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Tambah Data Ticket Tier</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Pilih Tour, centang jadwal yang belum punya tier, lalu isi tier sekali untuk diterapkan ke semua jadwal terpilih.</p>
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
                    <label class="form-label text-muted">Pilih Tour</label>
                    <select id="tourSelect" class="form-select">
                        <option value="">-- Pilih Tour --</option>
                        @foreach ($tours as $tour)
                            @if (!empty($jadwalsByTour[$tour->id]))
                                <option value="{{ $tour->id }}">{{ $tour->artist->nama_grup }} &mdash; {{ $tour->nama_tour }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div id="jadwalCheckWrap" class="mb-4" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label text-muted mb-0">Pilih Jadwal (belum ada tier)</label>
                        <button type="button" id="btnSelectAll" class="admin-btn-outline" style="padding: 4px 14px; font-size: 0.7rem;">Pilih Semua</button>
                    </div>

                    <div class="jadwal-check-list">
                        @foreach ($jadwalsByTour as $tourId => $jadwals)
                            <div class="jadwal-check-group" data-tour-group="{{ $tourId }}" style="display: none;">
                                @foreach ($jadwals as $jadwal)
                                    <label class="jadwal-check-item">
                                        <input type="checkbox" name="jadwal_ids[]" value="{{ $jadwal->id }}" class="jadwal-check-input" {{ in_array($jadwal->id, old('jadwal_ids', [])) ? 'checked' : '' }}>
                                        <span>{{ $jadwal->kota }} &mdash; {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    @error('jadwal_ids')
                        <div class="text-danger mt-2" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
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
let currentTourId = null;

document.getElementById('tourSelect').addEventListener('change', function () {
    document.querySelectorAll('.jadwal-check-group').forEach(function (group) {
        group.style.display = 'none';
        group.querySelectorAll('.jadwal-check-input').forEach(function (cb) {
            cb.checked = false;
        });
    });

    var wrap = document.getElementById('jadwalCheckWrap');
    currentTourId = this.value || null;

    if (!currentTourId) {
        wrap.style.display = 'none';
        return;
    }

    var target = document.querySelector('.jadwal-check-group[data-tour-group="' + currentTourId + '"]');
    if (target) {
        target.style.display = 'block';
        wrap.style.display = 'block';
    } else {
        wrap.style.display = 'none';
    }
});

document.getElementById('btnSelectAll').addEventListener('click', function () {
    if (!currentTourId) return;

    var group = document.querySelector('.jadwal-check-group[data-tour-group="' + currentTourId + '"]');
    if (!group) return;

    var checkboxes = group.querySelectorAll('.jadwal-check-input');
    var semuaTercentang = Array.from(checkboxes).every(function (cb) { return cb.checked; });

    checkboxes.forEach(function (cb) {
        cb.checked = !semuaTercentang;
    });
});

@if (old('jadwal_ids'))
    (function () {
        var firstChecked = document.querySelector('.jadwal-check-input:checked');
        if (firstChecked) {
            var group = firstChecked.closest('.jadwal-check-group');
            if (group) {
                var tourId = group.getAttribute('data-tour-group');
                document.getElementById('tourSelect').value = tourId;
                currentTourId = tourId;
                group.style.display = 'block';
                document.getElementById('jadwalCheckWrap').style.display = 'block';
            }
        }
    })();
@endif

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