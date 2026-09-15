@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container pb-5">

    <a href="{{ route('artist.show', ['id' => $jadwal->tour->artist->id, 'from' => request('from')]) }}" class="back-link">&lt; Kembali</a>

    <div class="tier-hero">
        <div class="tier-banner"
            @if ($jadwal->tour->foto_banner_detail) style="background-image: url('{{ asset('storage/' . $jadwal->tour->foto_banner_detail) }}');" @endif>
            <div class="tier-banner-caption">
                <h1>{{ $jadwal->tour->nama_tour }}</h1>
            </div>
        </div>

        <div class="tier-info-box">
            <div class="tier-info-row">
                <span class="tier-info-label">Kota</span>
                <span class="tier-info-value tier-info-accent">{{ $jadwal->kota }}</span>
            </div>
            <div class="tier-info-row">
                <span class="tier-info-label">Venue</span>
                <span class="tier-info-value">{{ $jadwal->venue }}</span>
            </div>
            <div class="tier-info-row">
                <span class="tier-info-label">Tanggal</span>
                <span class="tier-info-value">
                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                    @if ($jadwal->jam)
                        &middot; {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} {{ $jadwal->timezone }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <h4 class="tour-dates-title mt-5">Pilih Tier &amp; Jumlah Tiket</h4>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

        <div class="tier-list">
            @foreach ($jadwal->ticketTiers as $tier)
                <div class="tier-card">

                    <div class="tier-name">
                        <h5>{{ $tier->nama_tier }}</h5>
                    </div>

                    <div class="tier-price">
                        <p>Rp {{ number_format($tier->harga, 0, ',', '.') }}</p>
                    </div>

                    <div class="tier-quota">
                        <p>Kuota: {{ $tier->kuota }}</p>
                    </div>

                    <div class="tier-action">
                        <div class="tier-qty">
                            <button type="button" class="qty-btn btn-kurang" data-tier="{{ $tier->id }}" aria-label="Kurangi jumlah"></button>
                            <input type="number" class="qty-input input-jumlah" id="jumlah-{{ $tier->id }}"
                                name="tiers[{{ $tier->id }}]" data-harga="{{ $tier->harga }}"
                                value="0" min="0" max="{{ $tier->kuota }}" readonly>
                            <button type="button" class="qty-btn btn-tambah" data-tier="{{ $tier->id }}" aria-label="Tambah jumlah"></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="helper-text">*Maksimal 2 tiket per akun</p>

        <div class="tier-total">
            <h5>Total</h5>
            <h5 id="total-harga" class="tier-total-amount">Rp 0</h5>
        </div>

        @guest
            <a href="{{ route('login') }}" class="btn-accent-solid w-100">PROCEED TO CHECKOUT</a>
            <p class="helper-text mt-4">*Kamu perlu login/register dulu sebelum checkout</p>
        @else
            <button type="submit" class="btn-accent-solid w-100" id="btn-checkout">PROCEED TO CHECKOUT</button>
        @endguest

    </form>

</div>
@endsection

@push('scripts')
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
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal',
                    text: 'Maksimal 2 tiket per akun.',
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

            if (jumlahSekarang >= kuotaMax) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kuota Habis',
                    text: 'Kuota tier ini sudah habis.',
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