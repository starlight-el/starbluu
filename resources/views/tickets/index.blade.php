@extends('layouts.app')

@section('body-class', 'starbluu-theme')

@section('content')
<div class="container pb-5">

    <h3 class="page-title">My Tickets</h3>

    <div class="row mb-5">
        @forelse ($orders as $order)
            @php
                $tour = $order->ticketTier->jadwal->tour;
                $jadwal = $order->ticketTier->jadwal;
            @endphp
            <div class="col-md-4 col-sm-6 mb-5">
                <div class="ticket-card-frame">
                    <div class="tour-card"
                        @if ($tour->artist->foto_thumbnail) style="background-image: url('{{ asset('storage/' . $tour->artist->foto_thumbnail) }}');" @endif>

                        @if ($order->status === 'paid')
                            <span class="ticket-badge ticket-badge-paid">Lunas</span>
                        @elseif ($order->status === 'pending')
                            <span class="ticket-badge ticket-badge-pending">Menunggu Bayar</span>
                        @elseif ($order->status === 'cancelled')
                            <span class="ticket-badge ticket-badge-inactive">Dibatalkan</span>
                        @elseif ($order->status === 'expired')
                            <span class="ticket-badge ticket-badge-inactive">Expired</span>
                        @endif

                        <div class="tour-card-body">
                            <h5><span>{{ $tour->nama_tour }}</span></h5>
                            <p>{{ $jadwal->kota }}, {{ $jadwal->venue }} &middot; {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <span class="ticket-tier-chip">{{ $order->ticketTier->nama_tier }} x {{ $order->jumlah_tiket }}</span>

                    @if ($order->status === 'paid')
                        <a href="{{ route('eticket.show', $order->id) }}" class="btn-accent-solid">LIHAT E-TICKET</a>
                    @elseif ($order->status === 'pending')
                        <a href="{{ route('payment.show', $order->checkout_group_id) }}" class="btn-accent-solid">LANJUT BAYAR</a>
                    @elseif ($order->status === 'cancelled' || $order->status === 'expired')
                        <button type="button" class="btn-disabled-outline" disabled>LIHAT E-TICKET</button>
                    @endif
                </div>
            </div>
        @empty
            <p class="helper-text">Kamu belum punya pesanan tiket.</p>
        @endforelse
    </div>

</div>
@endsection