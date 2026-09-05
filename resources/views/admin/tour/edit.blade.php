@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-1">Ubah Data Tour & Jadwal</h3>
<p class="text-muted mb-4">Data lama sudah terisi otomatis, ubah field yang diperlukan lalu simpan.</p>

@php
    $oldJadwals = old('jadwals');
    if (!$oldJadwals) {
        $oldJadwals = $tour->jadwals->map(function ($j) {
            return [
                'id' => $j->id,
                'negara' => $j->negara,
                'kota' => $j->kota,
                'venue' => $j->venue,
                'tanggal' => $j->tanggal,
                'jam' => $j->jam ? substr($j->jam, 0, 5) : '',
                'timezone' => $j->timezone,
            ];
        })->toArray();
    }
    $oldDeletedJadwals = old('deleted_jadwals', []);
@endphp

<form action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label text-muted">Artist</label>
        <select name="artist_id" class="form-select" required>
            @foreach ($artists as $artist)
                <option value="{{ $artist->id }}" {{ old('artist_id', $tour->artist_id) == $artist->id ? 'selected' : '' }}>{{ $artist->nama_grup }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Nama Tour</label>
        <input type="text" name="nama_tour" class="form-control" value="{{ old('nama_tour', $tour->nama_tour) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Kategori</label>
        <select name="kategori" class="form-select" required>
            <option value="tour" {{ old('kategori', $tour->kategori) == 'tour' ? 'selected' : '' }}>Tour</option>
            <option value="world_tour" {{ old('kategori', $tour->kategori) == 'world_tour' ? 'selected' : '' }}>World Tour</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Foto Banner Home Saat Ini</label><br>
        @if ($tour->foto_banner_home)
            <img src="{{ Storage::url($tour->foto_banner_home) }}" alt="Banner Home" style="width: 200px; height: 100px; object-fit: cover; border-radius: 8px;" class="mb-2">
        @endif
        <label class="form-label text-muted d-block">Ganti Foto Banner Home (kosongkan jika tidak diubah)</label>
        <input type="file" name="foto_banner_home" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Foto Banner Detail Artist Saat Ini</label><br>
        @if ($tour->foto_banner_detail)
            <img src="{{ Storage::url($tour->foto_banner_detail) }}" alt="Banner Detail" style="width: 200px; height: 100px; object-fit: cover; border-radius: 8px;" class="mb-2">
        @endif
        <label class="form-label text-muted d-block">Ganti Foto Banner Detail Artist (kosongkan jika tidak diubah)</label>
        <input type="file" name="foto_banner_detail" class="form-control" accept="image/*">
    </div>

    <label class="form-label fw-bold">Jadwal</label>
    <div id="jadwal-list">
        @foreach ($oldJadwals as $index => $jadwal)
            <div class="jadwal-row border rounded p-3 mb-2">
                @if (!empty($jadwal['id']))
                    <input type="hidden" name="jadwals[{{ $index }}][id]" value="{{ $jadwal['id'] }}">
                @endif
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="jadwals[{{ $index }}][negara]" class="form-control" value="{{ $jadwal['negara'] ?? '' }}" placeholder="Negara" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="jadwals[{{ $index }}][kota]" class="form-control" value="{{ $jadwal['kota'] ?? '' }}" placeholder="Kota" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="jadwals[{{ $index }}][venue]" class="form-control" value="{{ $jadwal['venue'] ?? '' }}" placeholder="Venue" required>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="jadwals[{{ $index }}][tanggal]" class="form-control" value="{{ $jadwal['tanggal'] ?? '' }}" required>
                    </div>
                    <div class="col-md-3">
                        <input type="time" name="jadwals[{{ $index }}][jam]" class="form-control" value="{{ $jadwal['jam'] ?? '' }}" placeholder="Jam">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="jadwals[{{ $index }}][timezone]" class="form-control" value="{{ $jadwal['timezone'] ?? '' }}" placeholder="Timezone (mis. KST)">
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="button" class="btn btn-outline-danger btn-remove-jadwal" data-jadwal-id="{{ $jadwal['id'] ?? '' }}">x</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="deleted-jadwals-container">
        @foreach ($oldDeletedJadwals as $deletedId)
            <input type="hidden" name="deleted_jadwals[]" value="{{ $deletedId }}">
        @endforeach
    </div>

    <button type="button" id="btn-add-jadwal" class="btn btn-outline-dark btn-sm mb-4">+ TAMBAH JADWAL</button>

    <div>
        <button type="submit" class="btn btn-dark">SIMPAN</button>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">BATAL</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
let jadwalIndex = {{ count($oldJadwals) }};

document.getElementById('btn-add-jadwal').addEventListener('click', function () {
    const container = document.getElementById('jadwal-list');

    const row = document.createElement('div');
    row.className = 'jadwal-row border rounded p-3 mb-2';
    row.innerHTML = `
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="jadwals[${jadwalIndex}][negara]" class="form-control" placeholder="Negara" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="jadwals[${jadwalIndex}][kota]" class="form-control" placeholder="Kota" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="jadwals[${jadwalIndex}][venue]" class="form-control" placeholder="Venue" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="jadwals[${jadwalIndex}][tanggal]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="time" name="jadwals[${jadwalIndex}][jam]" class="form-control" placeholder="Jam">
            </div>
            <div class="col-md-3">
                <input type="text" name="jadwals[${jadwalIndex}][timezone]" class="form-control" placeholder="Timezone (mis. KST)">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger btn-remove-jadwal">x</button>
            </div>
        </div>
    `;

    container.appendChild(row);
    jadwalIndex++;
});

document.getElementById('jadwal-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-jadwal')) {
        const rows = document.querySelectorAll('.jadwal-row');

        if (rows.length <= 1) {
            alert('Minimal harus ada 1 jadwal.');
            return;
        }

        const jadwalId = e.target.getAttribute('data-jadwal-id');

        if (jadwalId) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deleted_jadwals[]';
            hiddenInput.value = jadwalId;
            document.getElementById('deleted-jadwals-container').appendChild(hiddenInput);
        }

        e.target.closest('.jadwal-row').remove();
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