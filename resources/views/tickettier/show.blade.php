@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{ route('artist.show', ['id' => $jadwal->tour->artist->id, 'from' => request('from')]) }}">&lt; Kembali</a>

    <h3 class="mt-3">{{ $jadwal->tour->nama_tour }}</h3>
    <p class="text-muted">
        {{ $jadwal->kota }}, {{ $jadwal->venue }} — 
        {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y.m.d') }}
        @if ($jadwal->jam)
            {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} {{ $jadwal->timezone }}
        @endif
    </p>

    @foreach ($jadwal->ticketTiers as $tier)
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ $tier->nama_tier }}</h5>
                    <p class="mb-1">Rp {{ number_format($tier->harga, 0, ',', '.') }}</p>
                    <p class="mb-0 text-muted">Kuota: {{ $tier->kuota }}</p>
                </div>

                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-outline-secondary btn-kurang" data-tier="{{ $tier->id }}">-</button>
                    <input type="number" class="form-control text-center mx-2 input-jumlah px-1"
                        style="width: 40px" id="jumlah-{{ $tier->id }}" data-harga="{{ $tier->harga }}"
                        value="0" min="0" max="{{ $tier->kuota }}" readonly>
                    <button type="button" class="btn btn-outline-secondary btn-tambah" data-tier="{{ $tier->id }}">+</button>
                </div>
            </div>
        </div>
    @endforeach

    <p class="text-muted">*Maksimal 2 tiket per akun</p>

    <hr>

    <div class="d-flex justify-content-between align-items-center">
        <h5>Total</h5>
        <h5 id="total-harga">Rp 0</h5>
    </div>

    <button type="button" class="btn btn-dark w-100 mt-2" id="btn-checkout">PROCEED TO CHECKOUT</button>

    <p class="text-muted mt-2">*Jika belum login, akan diarahkan ke halaman Login/Register terlebih dahulu</p>

</div>
@endsection

@push('scripts')
<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const maxTiketPerAkun = 2;

    function hitungTotalTiket() {
        let total = 0;
        document.querySelectorAll('.input-jumlah').forEach(function (input) {
            total += parseInt(input.value);
        });
        return total;
    }

    function updateTotalHarga() {
        let totalHarga = 0;
        document.querySelectorAll('.input-jumlah').forEach(function (input) {
            const harga = parseInt(input.dataset.harga);
            const jumlah = parseInt(input.value);
            totalHarga += harga * jumlah;
        });

        document.getElementById('total-harga').innerText =
            'Rp ' + totalHarga.toLocaleString('id-ID');
    }

    document.querySelectorAll('.btn-tambah').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const tierId = this.dataset.tier;
            const input = document.getElementById('jumlah-' + tierId);
            const kuotaMax = parseInt(input.max);
            const jumlahSekarang = parseInt(input.value);

            if (hitungTotalTiket() >= maxTiketPerAkun) {
                alert('Maksimal 2 tiket per akun.');
                return;
            }

            if (jumlahSekarang >= kuotaMax) {
                alert('Kuota tier ini sudah habis.');
                return;
            }

            input.value = jumlahSekarang + 1;
            updateTotalHarga();
        });
    });

    document.querySelectorAll('.btn-kurang').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const tierId = this.dataset.tier;
            const input = document.getElementById('jumlah-' + tierId);
            const jumlahSekarang = parseInt(input.value);

            if (jumlahSekarang > 0) {
                input.value = jumlahSekarang - 1;
                updateTotalHarga();
            }
        });
    });
});
</script>
@endpush