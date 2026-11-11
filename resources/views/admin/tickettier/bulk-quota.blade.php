@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Atur Kuota Sekaligus</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Pilih Tour, centang jadwal, lalu isi satu angka kuota untuk semua tier pada jadwal terpilih. Nama tier dan harga tidak berubah.</p>
            </div>
        </div>

        <div class="admin-form-card mt-4">
            <form action="{{ route('admin.tickettiers.bulkquota.update') }}" method="POST" id="bulkQuotaForm">
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
                        <label class="form-label text-muted mb-0">Pilih Jadwal</label>
                        <div class="d-flex gap-2">
                            <button type="button" id="btnSelectPast" class="admin-btn-outline" style="padding: 4px 14px; font-size: 0.7rem;">Pilih yang Sudah Lewat</button>
                            <button type="button" id="btnSelectAll" class="admin-btn-outline" style="padding: 4px 14px; font-size: 0.7rem;">Pilih Semua</button>
                        </div>
                    </div>

                    <div class="jadwal-check-list">
                        @foreach ($jadwalsByTour as $tourId => $jadwals)
                            <div class="jadwal-check-group" data-tour-group="{{ $tourId }}" style="display: none;">
                                @foreach ($jadwals as $jadwal)
                                    @php
                                        $lewat = \Carbon\Carbon::parse($jadwal->tanggal)->isBefore(\Carbon\Carbon::today());
                                    @endphp
                                    <label class="jadwal-check-item">
                                        <input type="checkbox" name="jadwal_ids[]" value="{{ $jadwal->id }}" class="jadwal-check-input" data-lewat="{{ $lewat ? '1' : '0' }}" {{ in_array($jadwal->id, old('jadwal_ids', [])) ? 'checked' : '' }}>
                                        <span>{{ $jadwal->kota }} &mdash; {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}</span>
                                        <span class="jadwal-check-meta">
                                            @if ($lewat)Sudah lewat &middot; @endif
                                            Kuota tersisa: {{ $jadwal->ticket_tiers_sum_kuota }}
                                        </span>
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

                <div class="mb-4">
                    <label class="form-label text-muted">Kuota Baru (diterapkan ke setiap tier)</label>
                    <input type="number" id="kuotaInput" name="kuota" class="form-control @error('kuota') is-invalid @enderror" value="{{ old('kuota') }}" min="0" placeholder="mis. 0 untuk Sold Out" required>
                </div>

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

document.getElementById('btnSelectPast').addEventListener('click', function () {
    if (!currentTourId) return;

    var group = document.querySelector('.jadwal-check-group[data-tour-group="' + currentTourId + '"]');
    if (!group) return;

    group.querySelectorAll('.jadwal-check-input').forEach(function (cb) {
        cb.checked = cb.getAttribute('data-lewat') === '1';
    });
});

document.getElementById('bulkQuotaForm').addEventListener('submit', function (e) {
    var form = this;
    var jumlah = document.querySelectorAll('.jadwal-check-input:checked').length;
    var kuota = document.getElementById('kuotaInput').value;

    if (jumlah === 0 || kuota === '') {
        return;
    }

    e.preventDefault();

    Swal.fire({
        icon: 'warning',
        title: 'Konfirmasi Perubahan',
        text: 'Ubah kuota semua tier pada ' + jumlah + ' jadwal menjadi ' + kuota + '?',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true,
        buttonsStyling: false,
        customClass: {
            popup: 'bluu-swal-popup',
            title: 'bluu-swal-title',
            htmlContainer: 'bluu-swal-text',
            confirmButton: 'bluu-swal-confirm',
            cancelButton: 'bluu-swal-cancel',
        },
    }).then(function (result) {
        if (result.isConfirmed) {
            form.submit();
        }
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