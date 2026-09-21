@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Ubah Data Ticket Tier</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Data lama sudah terisi otomatis, ubah field yang diperlukan lalu simpan.</p>
            </div>
        </div>

        @php
            $oldTiers = old('tiers');
            if (empty($oldTiers)) {
                $oldTiers = $jadwal->ticketTiers->map(function ($tier) {
                    return [
                        'id' => $tier->id,
                        'nama_tier' => $tier->nama_tier,
                        'harga' => $tier->harga,
                        'kuota' => $tier->kuota,
                    ];
                })->toArray();
            }

            $oldDeletedTiers = old('deleted_tiers');
            if (empty($oldDeletedTiers)) {
                $oldDeletedTiers = [];
            }
        @endphp

        <div class="admin-form-card mt-4">
            <div class="mb-4">
                <label class="form-label text-muted">Jadwal Terkait</label>
                <input type="text" class="form-control" value="{{ $jadwal->tour->artist->nama_grup }} - {{ $jadwal->tour->nama_tour }} ({{ $jadwal->kota }}, {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }})" disabled>
            </div>

            <form action="{{ route('admin.tickettiers.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <hr style="border-color: rgba(255,255,255,0.08); margin: 32px 0;">

                <h6 class="fw-bold text-white mb-3" style="font-size: 0.95rem;">Tier</h6>
                <div id="tier-list">
                    @foreach ($oldTiers as $index => $tier)
                        <div class="tier-row">
                            @if (!empty($tier['id']))
                                <input type="hidden" name="tiers[{{ $index }}][id]" value="{{ $tier['id'] }}">
                            @endif
                            <select name="tiers[{{ $index }}][nama_tier]" class="form-select tier-select" required>
                                <option value="">-- Nama Tier --</option>
                                @foreach (['VIP Soundcheck', 'Floor/Standing', 'CAT 1', 'CAT 2', 'CAT 3'] as $opsi)
                                    <option value="{{ $opsi }}" {{ ($tier['nama_tier'] ?? '') === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="tiers[{{ $index }}][harga]" class="form-control tier-price" value="{{ $tier['harga'] ?? '' }}" placeholder="Harga (Rp)" min="0" required>
                            <input type="number" name="tiers[{{ $index }}][kuota]" class="form-control tier-quota" value="{{ $tier['kuota'] ?? '' }}" placeholder="Kuota" min="0" required>
                            <button type="button" class="tier-remove-btn btn-remove-tier" data-tier-id="{{ $tier['id'] ?? '' }}" aria-label="Hapus tier"></button>
                        </div>
                    @endforeach
                </div>

                <div id="deleted-tiers-container">
                    @foreach ($oldDeletedTiers as $deletedId)
                        <input type="hidden" name="deleted_tiers[]" value="{{ $deletedId }}">
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

        if (rows.length <= 1) {
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
            return;
        }

        const tierId = e.target.getAttribute('data-tier-id');

        if (tierId) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deleted_tiers[]';
            hiddenInput.value = tierId;
            document.getElementById('deleted-tiers-container').appendChild(hiddenInput);
        }

        e.target.closest('.tier-row').remove();
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