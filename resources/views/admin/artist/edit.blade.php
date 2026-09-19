@extends('layouts.admin')

@section('content')
    <div class="admin-container">

        <div class="admin-page-header">
            <div>
                <h3 class="fw-bold mb-1">Ubah Data Artist</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Data lama (termasuk foto & daftar member) sudah terisi otomatis, ubah field yang diperlukan lalu simpan.</p>
            </div>
        </div>

        @php
            $oldMembers = old('members');
            if (empty($oldMembers)) {
                $oldMembers = $artist->artistMembers->map(function ($m) {
                    return ['id' => $m->id, 'nama_member' => $m->nama_member];
                })->toArray();
            }

            $oldDeletedMembers = old('deleted_members');
            if (empty($oldDeletedMembers)) {
                $oldDeletedMembers = [];
            }
        @endphp

        <div class="admin-form-card mt-4">
            <form action="{{ route('admin.artists.update', $artist->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="artist-form-grid">
                    <div>
                        <div class="mb-4">
                            <label class="form-label text-muted">Nama Grup</label>
                            <input type="text" name="nama_grup" class="form-control @error('nama_grup') is-invalid @enderror" value="{{ old('nama_grup', $artist->nama_grup) }}" required>
                        </div>
                        <div>
                            <label class="form-label text-muted">Foto Thumbnail</label>
                            <div class="d-flex gap-3 align-items-center">
                                @if ($artist->foto_thumbnail)
                                    <img src="{{ Storage::url($artist->foto_thumbnail) }}" alt="{{ $artist->nama_grup }}" class="admin-photo-thumb" style="width: 52px; height: 52px;">
                                @endif
                                <div class="flex-grow-1">
                                    <div class="file-input-wrap @error('foto_thumbnail') is-invalid @enderror">
                                        <input type="file" id="thumbnail-input-edit" name="foto_thumbnail" class="file-input-hidden" accept="image/*">
                                        <label for="thumbnail-input-edit" class="file-input-button">Choose File</label>
                                        <span class="file-input-filename">No file chosen</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column h-100">
                        <label class="form-label text-muted">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control flex-grow-1 w-100" style="resize: none; height: 0px; min-height: 0px;">{{ old('deskripsi', $artist->deskripsi) }}</textarea>
                    </div>
                </div>

                <hr style="border-color: rgba(255,255,255,0.08); margin: 32px 0;">

                <h6 class="fw-bold text-white mb-3" style="font-size: 0.95rem;">Anggota / Member</h6>
                <div id="member-list">
                    @foreach ($oldMembers as $index => $member)
                        @php
                            $existingMember = !empty($member['id']) ? $artist->artistMembers->firstWhere('id', $member['id']) : null;
                        @endphp
                        <div class="artist-member-row">
                            @if ($existingMember && $existingMember->foto_member)
                                <img src="{{ Storage::url($existingMember->foto_member) }}" alt="{{ $existingMember->nama_member }}" class="artist-member-avatar">
                            @else
                                <div class="artist-member-avatar-placeholder"></div>
                            @endif

                            @if (!empty($member['id']))
                                <input type="hidden" name="members[{{ $index }}][id]" value="{{ $member['id'] }}">
                            @endif

                            <div class="file-input-wrap artist-member-file">
                                <input type="file" id="member-foto-{{ $index }}" name="members[{{ $index }}][foto_member]" class="file-input-hidden" accept="image/*">
                                <label for="member-foto-{{ $index }}" class="file-input-button">Choose File</label>
                                <span class="file-input-filename">No file chosen</span>
                            </div>
                            <input type="text" name="members[{{ $index }}][nama_member]" class="form-control artist-member-name" value="{{ $member['nama_member'] ?? '' }}" placeholder="Nama Member" required>
                            <button type="button" class="artist-member-remove btn-remove-member" data-member-id="{{ $member['id'] ?? '' }}" aria-label="Hapus member"></button>
                        </div>
                    @endforeach
                </div>

                <div id="deleted-members-container">
                    @foreach ($oldDeletedMembers as $deletedId)
                        <input type="hidden" name="deleted_members[]" value="{{ $deletedId }}">
                    @endforeach
                </div>

                <button type="button" id="btn-add-member" class="admin-btn-success mb-4" style="padding: 8px 20px; font-size: 0.75rem; margin-top: 4px;">+ Tambah Member</button>

                <div class="admin-form-actions pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                    <button type="submit" class="admin-btn-solid">Simpan</button>
                    <a href="{{ route('admin.artists.index') }}" class="admin-btn-outline">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
<script>
let memberIndex = {{ count($oldMembers) }};

document.getElementById('btn-add-member').addEventListener('click', function () {
    const container = document.getElementById('member-list');

    const row = document.createElement('div');
    row.className = 'artist-member-row';
    row.innerHTML = `
        <div class="artist-member-avatar-placeholder"></div>
        <div class="file-input-wrap artist-member-file">
            <input type="file" id="member-foto-${memberIndex}" name="members[${memberIndex}][foto_member]" class="file-input-hidden" accept="image/*">
            <label for="member-foto-${memberIndex}" class="file-input-button">Choose File</label>
            <span class="file-input-filename">No file chosen</span>
        </div>
        <input type="text" name="members[${memberIndex}][nama_member]" class="form-control artist-member-name" placeholder="Nama Member" required>
        <button type="button" class="artist-member-remove btn-remove-member" aria-label="Hapus member"></button>
    `;

    container.appendChild(row);
    memberIndex++;
});

document.getElementById('member-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-member')) {
        const rows = document.querySelectorAll('.artist-member-row');

        if (rows.length <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak bisa dihapus',
                text: 'Minimal harus ada 1 member.',
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

        const memberId = e.target.getAttribute('data-member-id');

        if (memberId) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deleted_members[]';
            hiddenInput.value = memberId;
            document.getElementById('deleted-members-container').appendChild(hiddenInput);
        }

        e.target.closest('.artist-member-row').remove();
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