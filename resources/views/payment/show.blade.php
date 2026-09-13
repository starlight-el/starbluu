@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container">

    <h3 class="page-title">Simulasi Pembayaran</h3>

    <div class="expiry-timer">
        <div class="expiry-timer-fill" id="timer-fill"></div>
        <div class="expiry-timer-label">
            Sisa waktu pembayaran <strong id="timer-text">--:--</strong>
        </div>
    </div>

    <p class="payment-total-label">Total Tagihan</p>
    <h3 class="payment-total-amount">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h3>

    <form method="POST" action="{{ route('payment.process', $checkoutGroupId) }}" id="form-pembayaran">
        @csrf

        <h4 class="tour-dates-title">Pilih Metode Pembayaran</h4>

        <div class="payment-method-grid">
            <div class="payment-method-card">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="kategori_pembayaran"
                        id="kategori-bank" value="transfer_bank" checked>
                    <label class="form-check-label" for="kategori-bank">
                        Transfer Bank
                    </label>
                </div>
                <select class="form-select" name="bank" id="pilihan-bank">
                    <option value="">Pilih Bank</option>
                    <option value="BCA">BCA</option>
                    <option value="BNI">BNI</option>
                    <option value="Mandiri">Mandiri</option>
                </select>
            </div>

            <div class="payment-method-card">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="kategori_pembayaran"
                        id="kategori-ewallet" value="e_wallet">
                    <label class="form-check-label" for="kategori-ewallet">
                        E-Wallet
                    </label>
                </div>
                <select class="form-select" name="e_wallet" id="pilihan-ewallet" disabled>
                    <option value="">Pilih E-Wallet</option>
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="DANA">DANA</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn-accent-solid" id="btn-bayar">BAYAR SEKARANG</button>

    </form>

    <p class="helper-text text-center mt-5 pt-5 mb-4">*Simulasi: status pembayaran langsung dikonfirmasi "Lunas" jika masih dalam batas waktu checkout.</p>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const expiredAt = new Date("{{ $expiredAt->toIso8601String() }}").getTime();
    const totalDurationMs = {{ config('starbluu.checkout_expiry_minutes') }} * 60 * 1000;
    const timerText = document.getElementById('timer-text');
    const timerFill = document.getElementById('timer-fill');
    const btnBayar = document.getElementById('btn-bayar');

    function updateTimer() {
        const now = new Date().getTime();
        const sisaMs = expiredAt - now;
        const sisaDetik = Math.floor(sisaMs / 1000);

        if (sisaDetik <= 0) {
            timerText.innerText = '00:00';
            timerFill.style.width = '0%';
            btnBayar.disabled = true;
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

    const radioBank = document.getElementById('kategori-bank');
    const radioEwallet = document.getElementById('kategori-ewallet');
    const pilihanBank = document.getElementById('pilihan-bank');
    const pilihanEwallet = document.getElementById('pilihan-ewallet');

    radioBank.addEventListener('change', function () {
        pilihanBank.disabled = false;
        pilihanEwallet.disabled = true;
        pilihanEwallet.value = '';
    });

    radioEwallet.addEventListener('change', function () {
        pilihanEwallet.disabled = false;
        pilihanBank.disabled = true;
        pilihanBank.value = '';
    });
});
</script>
@endpush