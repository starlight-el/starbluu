@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Checkout</h3>

    <div class="bg-dark text-white text-center py-3 rounded mb-4">
        Selesaikan pembayaran dalam <strong id="timer">--:--</strong>
    </div>

    <h5>Ringkasan Pesanan</h5>

    <p class="mb-1"><strong>Tour</strong></p>
    <p class="text-muted">{{ $orders->first()->ticketTier->jadwal->tour->nama_tour }}</p>

    <p class="mb-1"><strong>Jadwal</strong></p>
    <p class="text-muted">
        {{ $orders->first()->ticketTier->jadwal->kota }}, {{ $orders->first()->ticketTier->jadwal->venue }} —
        {{ \Carbon\Carbon::parse($orders->first()->ticketTier->jadwal->tanggal)->format('Y.m.d') }}
    </p>

    @foreach ($orders as $order)
        <div class="d-flex justify-content-between border-bottom py-2">
            <div>
                <p class="mb-0"><strong>{{ $order->ticketTier->nama_tier }}</strong> x {{ $order->jumlah_tiket }}</p>
                <p class="mb-0 text-muted">Harga Satuan: Rp {{ number_format($order->ticketTier->harga, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="mb-0">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
            </div>
        </div>
    @endforeach

    <hr>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>Total Bayar</h5>
        <h5>Rp {{ number_format($totalBayar, 0, ',', '.') }}</h5>
    </div>

    <div class="row">
        <div class="col-6">
            <form method="POST" action="{{ route('checkout.cancel', $checkoutGroupId) }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100">BATALKAN PESANAN</button>
            </form>
        </div>
        <div class="col-6">
            <a href="{{ route('payment.show', $checkoutGroupId) }}" class="btn btn-dark w-100">LANJUT PEMBAYARAN</a>
        </div>
    </div>

    <p class="text-muted text-center mt-3">*Jika waktu habis dan belum dibayar, pesanan otomatis dibatalkan dan kuota tiket dikembalikan.</p>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const expiredAt = new Date("{{ $expiredAt->toIso8601String() }}").getTime();
    const timerEl = document.getElementById('timer');

    function updateTimer() {
        const now = new Date().getTime();
        const sisaDetik = Math.floor((expiredAt - now) / 1000);

        if (sisaDetik <= 0) {
            timerEl.innerText = '00:00';
            clearInterval(interval);
            window.location.reload();
            return;
        }

        const menit = Math.floor(sisaDetik / 60).toString().padStart(2, '0');
        const detik = (sisaDetik % 60).toString().padStart(2, '0');
        timerEl.innerText = menit + ':' + detik;
    }

    updateTimer();
    const interval = setInterval(updateTimer, 1000);
});
</script>
@endpush