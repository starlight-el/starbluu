@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-1">Ubah Data Artist</h3>
<p class="text-muted mb-4">Data lama (termasuk foto & daftar member) sudah terisi otomatis, ubah field yang diperlukan lalu simpan.</p>

@php
    $oldMembers = old('members');
    if (!$oldMembers) {
        $oldMembers = $artist->artistMembers->map(function ($m) {
            return ['id' => $m->id, 'nama_member' => $m->nama_member];
        })->toArray();
    }
    $oldDeletedMembers = old('deleted_members', []);
@endphp

<form action="{{ route('admin.artists.update', $artist->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label text-muted">Nama Grup</label>
        <input type="text" name="nama_grup" class="form-control" value="{{ old('nama_grup', $artist->nama_grup) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Foto Thumbnail Saat Ini</label><br>
        @if ($artist->foto_thumbnail)
            <img src="{{ Storage::url($artist->foto_thumbnail) }}" alt="{{ $artist->nama_grup }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;" class="mb-2">
        @endif
        <label class="form-label text-muted d-block">Ganti Foto Thumbnail (kosongkan jika tidak diubah)</label>
        <input type="file" name="foto_thumbnail" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $artist->deskripsi) }}</textarea>
    </div>

    <label class="form-label fw-bold">Anggota / Member</label>
    <div id="member-list">
        @foreach ($oldMembers as $index => $member)
            @php
                $existingMember = !empty($member['id']) ? $artist->artistMembers->firstWhere('id', $member['id']) : null;
            @endphp
            <div class="member-row d-flex align-items-center gap-2 mb-2">
                @if ($existingMember && $existingMember->foto_member)
                    <img src="{{ Storage::url($existingMember->foto_member) }}" alt="{{ $existingMember->nama_member }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; flex-shrink: 0;">
                @endif
                @if (!empty($member['id']))
                    <input type="hidden" name="members[{{ $index }}][id]" value="{{ $member['id'] }}">
                @endif
                <input type="file" name="members[{{ $index }}][foto_member]" class="form-control" accept="image/*" style="flex: 1 1 50%;">
                <input type="text" name="members[{{ $index }}][nama_member]" class="form-control" value="{{ $member['nama_member'] ?? '' }}" placeholder="Nama Member" required style="flex: 1 1 50%;">
                <button type="button" class="btn btn-outline-danger btn-remove-member flex-shrink-0" data-member-id="{{ $member['id'] ?? '' }}">x</button>
            </div>
        @endforeach
    </div>

    <div id="deleted-members-container">
        @foreach ($oldDeletedMembers as $deletedId)
            <input type="hidden" name="deleted_members[]" value="{{ $deletedId }}">
        @endforeach
    </div>

    <button type="button" id="btn-add-member" class="btn btn-outline-dark btn-sm mb-4">+ TAMBAH MEMBER</button>

    <div>
        <button type="submit" class="btn btn-dark">SIMPAN</button>
        <a href="{{ route('admin.artists.index') }}" class="btn btn-outline-secondary">BATAL</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
let memberIndex = {{ count($oldMembers) }};

document.getElementById('btn-add-member').addEventListener('click', function () {
    const container = document.getElementById('member-list');

    const row = document.createElement('div');
    row.className = 'member-row d-flex align-items-center gap-2 mb-2';
    row.innerHTML = `
        <input type="file" name="members[${memberIndex}][foto_member]" class="form-control" accept="image/*" style="flex: 1 1 50%;">
        <input type="text" name="members[${memberIndex}][nama_member]" class="form-control" placeholder="Nama Member" required style="flex: 1 1 50%;">
        <button type="button" class="btn btn-outline-danger btn-remove-member flex-shrink-0">x</button>
    `;

    container.appendChild(row);
    memberIndex++;
});

document.getElementById('member-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-member')) {
        const rows = document.querySelectorAll('.member-row');

        if (rows.length <= 1) {
            alert('Minimal harus ada 1 member.');
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

        e.target.closest('.member-row').remove();
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