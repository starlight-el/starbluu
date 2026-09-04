@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-1">Tambah Data Tour & Jadwal</h3>
<p class="text-muted mb-4">Isi seluruh data baru dari awal, semua kolom masih kosong.</p>

<form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label text-muted">Artist</label>
        <select name="artist_id" class="form-select" required>
            <option value="">-- Pilih Artist --</option>
            @foreach ($artists as $artist)
                <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>{{ $artist->nama_grup }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Nama Tour</label>
        <input type="text" name="nama_tour" class="form-control" value="{{ old('nama_tour') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Kategori</label>
        <select name="kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="tour" {{ old('kategori') == 'tour' ? 'selected' : '' }}>Tour</option>
            <option value="world_tour" {{ old('kategori') == 'world_tour' ? 'selected' : '' }}>World Tour</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Upload Foto Banner Home (Slider)</label>
        <input type="file" name="foto_banner_home" class="form-control" accept="image/*" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Upload Foto Banner Detail Artist</label>
        <input type="file" name="foto_banner_detail" class="form-control" accept="image/*" required>
    </div>

    <label class="form-label fw-bold">Jadwal</label>
    <div id="jadwal-list">
        <div class="jadwal-row border rounded p-3 mb-2">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="jadwals[0][negara]" class="form-control" placeholder="Negara" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="jadwals[0][kota]" class="form-control" placeholder="Kota" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="jadwals[0][venue]" class="form-control" placeholder="Venue" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="jadwals[0][tanggal]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <input type="time" name="jadwals[0][jam]" class="form-control" placeholder="Jam">
                </div>
                <div class="col-md-3">
                    <input type="text" name="jadwals[0][timezone]" class="form-control" placeholder="Timezone (mis. KST)">
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="button" class="btn btn-outline-danger btn-remove-jadwal">x</button>
                </div>
            </div>
        </div>
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
let jadwalIndex = 1;

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

        if (rows.length > 1) {
            e.target.closest('.jadwal-row').remove();
        } else {
            alert('Minimal harus ada 1 jadwal.');
        }
    }
});
</script>
@endpush