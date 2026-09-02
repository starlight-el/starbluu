@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Simulasi Pembayaran</h3>

    <div class="bg-dark text-white text-center py-3 rounded mb-4">
        Sisa waktu pembayaran <strong id="timer">--:--</strong>
    </div>

    <p class="mb-1">Total Tagihan</p>
    <h3 class="mb-4">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h3>

    <form method="POST" action="{{ route('payment.process', $checkoutGroupId) }}" id="form-pembayaran">
        @csrf

        <p class="fw-bold">Pilih Metode Pembayaran</p>

        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="border rounded p-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="kategori_pembayaran"
                            id="kategori-bank" value="transfer_bank" checked>
                        <label class="form-check-label fw-bold" for="kategori-bank">
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
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="kategori_pembayaran"
                            id="kategori-ewallet" value="e_wallet">
                        <label class="form-check-label fw-bold" for="kategori-ewallet">
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
        </div>

        <button type="submit" class="btn btn-dark w-100" id="btn-bayar">BAYAR SEKARANG</button>

    </form>

    <p class="text-muted text-center mt-3">*Simulasi: status pembayaran langsung dikonfirmasi "Lunas" jika masih dalam batas waktu checkout.</p>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const expiredAt = new Date("{{ $expiredAt->toIso8601String() }}").getTime();
    const timerEl = document.getElementById('timer');
    const btnBayar = document.getElementById('btn-bayar');

    function updateTimer() {
        const now = new Date().getTime();
        const sisaDetik = Math.floor((expiredAt - now) / 1000);

        if (sisaDetik <= 0) {
            timerEl.innerText = '00:00';
            btnBayar.disabled = true;
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