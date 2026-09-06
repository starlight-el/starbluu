@extends('layouts.admin')

@section('content')
    <h2 class="mb-1">Validasi Check-In Tiket</h2>
    <p class="text-muted mb-4">Masukkan kode e-ticket untuk memvalidasi kehadiran</p>

    <form method="POST" action="{{ route('admin.checkin.store') }}" style="max-width: 600px;">
        @csrf
        <div class="mb-3">
            <input
                type="text"
                name="kode_eticket"
                id="kodeEticket"
                class="form-control form-control-lg"
                placeholder="Kode E-Ticket (contoh: SB-CORTIS-000012)"
                value="{{ old('kode_eticket') }}"
                autofocus
            >
        </div>
        <button type="submit" class="btn btn-dark btn-lg mb-4">VALIDASI</button>
    </form>

    @if (session('checkin_result'))
        @php $result = session('checkin_result'); @endphp

        @if ($result['valid'])
            <div class="alert alert-success" style="max-width: 600px;">
                <strong>&#10003; VALID</strong> &mdash; Tiket {{ $result['nama_tier'] }}, atas nama {{ $result['nama_customer'] }}<br>
                {{ $result['nama_artist'] }} &mdash; {{ $result['nama_tour'] }}<br>
                {{ $result['kota'] }}, {{ $result['venue'] }} &mdash; {{ $result['tanggal'] }}@if ($result['jam']), {{ $result['jam'] }}@endif @if ($result['timezone']){{ $result['timezone'] }}@endif
            </div>
        @else
            <div class="alert alert-danger" style="max-width: 600px;">
                <strong>&#10007; TIDAK VALID</strong> &mdash; {{ $result['message'] }}
            </div>
        @endif
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('kodeEticket');
            if (input) {
                input.value = '';
                input.focus();
            }

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{!! $errors->first() !!}',
                    confirmButtonColor: '#212529',
                });
            @endif
        });
    </script>
@endpush