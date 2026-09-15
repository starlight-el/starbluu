@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">

    <h3 class="page-title">Checkout</h3>

    <div class="expiry-timer">
        <div class="expiry-timer-fill" id="timer-fill"></div>
        <div class="expiry-timer-label">
            Selesaikan pembayaran dalam <strong id="timer-text">--:--</strong>
        </div>
    </div>

    <div class="checkout-summary-box">
        <div class="tier-info-row">
            <span class="tier-info-label">Tour</span>
            <span class="tier-info-value">{{ $orders->first()->ticketTier->jadwal->tour->nama_tour }}</span>
        </div>
        <div class="tier-info-row">
            <span class="tier-info-label">Jadwal</span>
            <span class="tier-info-value">
                {{ $orders->first()->ticketTier->jadwal->kota }}, {{ $orders->first()->ticketTier->jadwal->venue }}
                &middot; {{ \Carbon\Carbon::parse($orders->first()->ticketTier->jadwal->tanggal)->format('d M Y') }}
            </span>
        </div>
    </div>

    <div class="checkout-item-list">
        @foreach ($orders as $order)
            <div class="checkout-item-row align-items-center">
                <div>
                    <p class="checkout-item-name">{{ $order->ticketTier->nama_tier }}</p>
                    <p class="checkout-item-sub">{{ $order->jumlah_tiket }} x Rp {{ number_format($order->ticketTier->harga, 0, ',', '.') }}</p>
                </div>
                <div class="text-end">
                    <p class="checkout-item-price fs-5">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="tier-total d-flex justify-content-between align-items-center my-3">
        <h5 class="mb-0">Total Bayar</h5>
        <h3 class="tier-total-amount mb-0">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h3>
    </div>

    <div class="checkout-actions">
        <form method="POST" action="{{ route('checkout.cancel', $checkoutGroupId) }}">
            @csrf
            <button type="submit" class="btn-danger-outline">BATALKAN PESANAN</button>
        </form>
        <a href="{{ route('payment.show', $checkoutGroupId) }}" class="btn-accent-solid">LANJUT PEMBAYARAN</a>
    </div>

    <p class="helper-text text-center mt-4 pt-5 mb-3">*Jika waktu habis dan belum dibayar, pesanan otomatis dibatalkan dan kuota tiket dikembalikan.</p>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const expiredAt = new Date("{{ $expiredAt->toIso8601String() }}").getTime();
    const totalDurationMs = {{ config('starbluu.checkout_expiry_minutes') }} * 60 * 1000;
    const timerText = document.getElementById('timer-text');
    const timerFill = document.getElementById('timer-fill');

    function updateTimer() {
        const now = new Date().getTime();
        const sisaMs = expiredAt - now;
        const sisaDetik = Math.floor(sisaMs / 1000);

        if (sisaDetik <= 0) {
            timerText.innerText = '00:00';
            timerFill.style.width = '0%';
            clearInterval(interval);
            window.location.reload();
            return;
        }

        const menit = Math.floor(sisaDetik / 60).toString().padStart(2, '0');
        const detik = (sisaDetik % 60).toString().padStart(2, '0');
        timerText.innerText = menit + ':' + detik;

        const persen = Math.max(0, Math.min(100, (sisaMs / totalDurationMs) * 100));
        timerFill.style.width = persen + '%';
    }

    updateTimer();
    const interval = setInterval(updateTimer, 1000);
});
</script>
@endpush