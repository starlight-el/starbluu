@extends('layouts.app')

@section('content')
<div class="container pb-5">

    <a href="{{ route('tickets.index') }}" class="d-inline-block mb-4 text-dark text-decoration-none fw-bold" style="font-family: 'Times New Roman', Times, serif;">&lt; Kembali ke My Tickets</a>

    @php
        $jumlahTiket = $order->tickets->count();
    @endphp

    @foreach ($order->tickets as $index => $ticket)
        <div class="mx-auto mb-5 bg-white" style="max-width: 550px; border: 1.5px dashed #888; border-radius: 45px; padding: 50px 40px; font-family: 'Times New Roman', Times, serif;">

            <div class="text-center mb-4">
                <h4 class="fw-bold mb-0" style="letter-spacing: 0.5px;">E-TICKET</h4>
            </div>

            <div class="text-center mb-4">
                <h6 class="fw-bold mb-0" style="font-size: 1.15rem;">{{ $order->ticketTier->jadwal->tour->nama_tour }}</h6>
            </div>

            <div class="text-center mb-4" style="color: #666;">
                <p class="mb-2" style="font-size: 1rem;">{{ $order->ticketTier->jadwal->venue }}</p>
                <p class="mb-0" style="font-size: 1rem;">
                    {{ \Carbon\Carbon::parse($order->ticketTier->jadwal->tanggal)->format('Y.m.d') }}
                    @if ($order->ticketTier->jadwal->jam)
                        ({{ \Carbon\Carbon::parse($order->ticketTier->jadwal->jam)->format('H:i') }})
                    @endif
                </p>
            </div>

            <div class="text-center mb-4">
                <p class="fw-bold mb-0" style="font-size: 1rem;">
                    Tier: {{ $order->ticketTier->nama_tier }}
                    @if ($jumlahTiket > 1)
                        | Tiket {{ $index + 1 }} dari {{ $jumlahTiket }}
                    @endif
                </p>
            </div>

            <div class="text-center mb-5">
                <p class="mb-0" style="font-size: 1rem;">Atas nama: {{ $order->user->name }}</p>
            </div>

            @php
                $jumlahBatang = 60;
            @endphp
            <div class="d-flex justify-content-center mb-3" style="height: 80px;">
                @for ($i = 0; $i < $jumlahBatang; $i++)
                    @php
                        $hash = crc32($ticket->kode_eticket . '-' . $i);
                    @endphp
                    <div style="width: {{ ($hash % 4) + 2 }}px; height: 100%; background-color: #000; margin-right: {{ ($hash % 2) + 2 }}px;"></div>
                @endfor
            </div>

            <div class="d-flex align-items-center justify-content-center mb-5" style="gap: 8px;">
                <span style="font-size: 0.95rem;">Kode: {{ $ticket->kode_eticket }}</span>
                <button type="button" class="btn-copy-kode" onclick="salinKode('{{ $ticket->kode_eticket }}', this)" title="Salin kode">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="#555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="5" y="5" width="9" height="9" rx="1"></rect>
                        <path d="M3 11V3a1 1 0 0 1 1-1h8"></path>
                    </svg>
                </button>
            </div>

            <p class="text-center mb-0" style="font-size: 0.75rem; color: #888;">Tunjukkan barcode ini pada saat check-in di venue</p>

        </div>
    @endforeach

</div>
@endsection

@push('scripts')
<style>
.btn-copy-kode {
    background: none;
    border: none;
    padding: 0;
    color: #6c757d;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
}

.btn-copy-kode:hover {
    color: #212529;
}
</style>

<script>
function salinKode(kode, btn) {
    navigator.clipboard.writeText(kode).then(function () {
        const svg = btn.querySelector('svg');
        const originalHtml = svg.innerHTML;

        svg.innerHTML = '<path d="M3 8l3 3 7-7"></path>';

        setTimeout(function () {
            svg.innerHTML = originalHtml;
        }, 1500);
    });
}
</script>
@endpush