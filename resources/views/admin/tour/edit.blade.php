@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Ubah Data Tour & Jadwal</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Data lama sudah terisi otomatis, ubah field yang diperlukan lalu simpan.</p>
            </div>
        </div>

        @php
            $oldJadwals = old('jadwals');
            if (empty($oldJadwals)) {
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

            $oldDeletedJadwals = old('deleted_jadwals');
            if (empty($oldDeletedJadwals)) {
                $oldDeletedJadwals = [];
            }
        @endphp

        <div class="admin-form-card mt-4">
            <form action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="artist-form-grid">
                    <div>
                        <div class="mb-4">
                            <label class="form-label text-muted">Artist</label>
                            <select name="artist_id" class="form-select @error('artist_id') is-invalid @enderror" required>
                                @foreach ($artists as $artist)
                                    <option value="{{ $artist->id }}" {{ old('artist_id', $tour->artist_id) == $artist->id ? 'selected' : '' }}>{{ $artist->nama_grup }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Nama Tour</label>
                            <input type="text" name="nama_tour" class="form-control @error('nama_tour') is-invalid @enderror" value="{{ old('nama_tour', $tour->nama_tour) }}" required>
                        </div>

                        <div>
                            <label class="form-label text-muted">Kategori</label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="tour" {{ old('kategori', $tour->kategori) == 'tour' ? 'selected' : '' }}>Tour</option>
                                <option value="world_tour" {{ old('kategori', $tour->kategori) == 'world_tour' ? 'selected' : '' }}>World Tour</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex flex-column h-100 justify-content-between">
                        <div class="tour-photo-block">
                            <div class="tour-photo-preview ratio-square">
                                @if ($tour->foto_banner_home)
                                    <img src="{{ Storage::url($tour->foto_banner_home) }}" alt="Banner Home">
                                @endif
                            </div>
                            <div class="tour-photo-fields">
                                <label class="form-label text-muted">Ganti Foto Banner Home</label>
                                <div class="file-input-wrap @error('foto_banner_home') is-invalid @enderror">
                                    <input type="file" id="banner-home-input-edit" name="foto_banner_home" class="file-input-hidden" accept="image/*">
                                    <label for="banner-home-input-edit" class="file-input-button">Choose File</label>
                                    <span class="file-input-filename">No file chosen</span>
                                </div>
                            </div>
                        </div>

                        <div class="tour-photo-block">
                            <div class="tour-photo-preview ratio-portrait">
                                @if ($tour->foto_banner_detail)
                                    <img src="{{ Storage::url($tour->foto_banner_detail) }}" alt="Banner Detail">
                                @endif
                            </div>
                            <div class="tour-photo-fields">
                                <label class="form-label text-muted">Ganti Foto Banner Detail Artist</label>
                                <div class="file-input-wrap @error('foto_banner_detail') is-invalid @enderror">
                                    <input type="file" id="banner-detail-input-edit" name="foto_banner_detail" class="file-input-hidden" accept="image/*">
                                    <label for="banner-detail-input-edit" class="file-input-button">Choose File</label>
                                    <span class="file-input-filename">No file chosen</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-color: rgba(255,255,255,0.08); margin: 32px 0;">

                <h6 class="fw-bold text-white mb-3" style="font-size: 0.95rem;">Jadwal</h6>
                <div id="jadwal-list">
                    @foreach ($oldJadwals as $index => $jadwal)
                        <div class="jadwal-card">
                            @if (!empty($jadwal['id']))
                                <input type="hidden" name="jadwals[{{ $index }}][id]" value="{{ $jadwal['id'] }}">
                            @endif
                            <button type="button" class="jadwal-remove-btn btn-remove-jadwal" data-jadwal-id="{{ $jadwal['id'] ?? '' }}" aria-label="Hapus jadwal"></button>
                            <div class="jadwal-card-grid">
                                <div>
                                    <label class="form-label text-muted">Negara</label>
                                    <input type="text" name="jadwals[{{ $index }}][negara]" class="form-control" value="{{ $jadwal['negara'] ?? '' }}" required>
                                </div>
                                <div>
                                    <label class="form-label text-muted">Kota</label>
                                    <input type="text" name="jadwals[{{ $index }}][kota]" class="form-control" value="{{ $jadwal['kota'] ?? '' }}" required>
                                </div>
                                <div>
                                    <label class="form-label text-muted">Venue</label>
                                    <input type="text" name="jadwals[{{ $index }}][venue]" class="form-control" value="{{ $jadwal['venue'] ?? '' }}" required>
                                </div>
                                <div>
                                    <label class="form-label text-muted">Tanggal</label>
                                    <input type="date" name="jadwals[{{ $index }}][tanggal]" class="form-control" value="{{ $jadwal['tanggal'] ?? '' }}" required>
                                </div>
                                <div>
                                    <label class="form-label text-muted">Jam</label>
                                    <input type="time" name="jadwals[{{ $index }}][jam]" class="form-control" value="{{ $jadwal['jam'] ?? '' }}">
                                </div>
                                <div>
                                    <label class="form-label text-muted">Timezone</label>
                                    <input type="text" name="jadwals[{{ $index }}][timezone]" class="form-control" value="{{ $jadwal['timezone'] ?? '' }}" placeholder="mis. KST">
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

                <button type="button" id="btn-add-jadwal" class="admin-btn-success mb-4" style="padding: 8px 20px; font-size: 0.75rem; margin-top: 4px;">+ Tambah Jadwal</button>

                <div class="admin-form-actions pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                    <button type="submit" class="admin-btn-solid">Simpan</button>
                    <a href="{{ route('admin.tours.index') }}" class="admin-btn-outline">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
<script>
let jadwalIndex = {{ count($oldJadwals) }};

document.getElementById('btn-add-jadwal').addEventListener('click', function () {
    const container = document.getElementById('jadwal-list');

    const card = document.createElement('div');
    card.className = 'jadwal-card';
    card.innerHTML = `
        <button type="button" class="jadwal-remove-btn btn-remove-jadwal" aria-label="Hapus jadwal"></button>
        <div class="jadwal-card-grid">
            <div>
                <label class="form-label text-muted">Negara</label>
                <input type="text" name="jadwals[${jadwalIndex}][negara]" class="form-control" required>
            </div>
            <div>
                <label class="form-label text-muted">Kota</label>
                <input type="text" name="jadwals[${jadwalIndex}][kota]" class="form-control" required>
            </div>
            <div>
                <label class="form-label text-muted">Venue</label>
                <input type="text" name="jadwals[${jadwalIndex}][venue]" class="form-control" required>
            </div>
            <div>
                <label class="form-label text-muted">Tanggal</label>
                <input type="date" name="jadwals[${jadwalIndex}][tanggal]" class="form-control" required>
            </div>
            <div>
                <label class="form-label text-muted">Jam</label>
                <input type="time" name="jadwals[${jadwalIndex}][jam]" class="form-control">
            </div>
            <div>
                <label class="form-label text-muted">Timezone</label>
                <input type="text" name="jadwals[${jadwalIndex}][timezone]" class="form-control" placeholder="mis. KST">
            </div>
        </div>
    `;

    container.appendChild(card);
    jadwalIndex++;
});

document.getElementById('jadwal-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-jadwal')) {
        const cards = document.querySelectorAll('.jadwal-card');

        if (cards.length <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak bisa dihapus',
                text: 'Minimal harus ada 1 jadwal.',
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

        const jadwalId = e.target.getAttribute('data-jadwal-id');

        if (jadwalId) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deleted_jadwals[]';
            hiddenInput.value = jadwalId;
            document.getElementById('deleted-jadwals-container').appendChild(hiddenInput);
        }

        e.target.closest('.jadwal-card').remove();
    }
});

document.addEventListener('change', function (e) {
    if (e.target.classList.contains('file-input-hidden')) {
        const wrap = e.target.closest('.file-input-wrap');
        const label = wrap.querySelector('.file-input-filename');
        label.textContent = e.target.files.length ? e.target.files[0].name : 'No file chosen';
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