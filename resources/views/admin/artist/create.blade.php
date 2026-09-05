@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-1">Tambah Data Artist</h3>
<p class="text-muted mb-4">Isi seluruh data baru dari awal, semua kolom masih kosong.</p>

@php
    $oldMembers = old('members');
    if (!$oldMembers) {
        $oldMembers = [['nama_member' => '']];
    }
@endphp

<form action="{{ route('admin.artists.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label text-muted">Nama Grup</label>
        <input type="text" name="nama_grup" class="form-control" value="{{ old('nama_grup') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Upload Foto Thumbnail</label>
        <input type="file" name="foto_thumbnail" class="form-control" accept="image/*" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-muted">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
    </div>

    <label class="form-label fw-bold">Anggota / Member</label>
    <div id="member-list">
        @foreach ($oldMembers as $index => $member)
            <div class="member-row d-flex align-items-center gap-2 mb-2">
                <input type="file" name="members[{{ $index }}][foto_member]" class="form-control" accept="image/*" required style="flex: 1 1 50%;">
                <input type="text" name="members[{{ $index }}][nama_member]" class="form-control" value="{{ $member['nama_member'] ?? '' }}" placeholder="Nama Member" required style="flex: 1 1 50%;">
                <button type="button" class="btn btn-outline-danger btn-remove-member flex-shrink-0">x</button>
            </div>
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
        <input type="file" name="members[${memberIndex}][foto_member]" class="form-control" accept="image/*" required style="flex: 1 1 50%;">
        <input type="text" name="members[${memberIndex}][nama_member]" class="form-control" placeholder="Nama Member" required style="flex: 1 1 50%;">
        <button type="button" class="btn btn-outline-danger btn-remove-member flex-shrink-0">x</button>
    `;

    container.appendChild(row);
    memberIndex++;
});

document.getElementById('member-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-member')) {
        const rows = document.querySelectorAll('.member-row');

        if (rows.length > 1) {
            e.target.closest('.member-row').remove();
        } else {
            alert('Minimal harus ada 1 member.');
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