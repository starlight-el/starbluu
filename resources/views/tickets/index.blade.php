@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">My Tickets</h3>

    @forelse ($orders as $order)
        <div class="border rounded p-3 mb-3 bg-light">
            <h5 class="mb-1">{{ $order->ticketTier->jadwal->tour->nama_tour }}</h5>

            <p class="text-muted mb-1">
                {{ $order->ticketTier->jadwal->kota }}, {{ $order->ticketTier->jadwal->venue }} —
                {{ \Carbon\Carbon::parse($order->ticketTier->jadwal->tanggal)->format('Y.m.d') }}
            </p>

            <p class="mb-3">{{ $order->ticketTier->nama_tier }} x {{ $order->jumlah_tiket }}</p>

            <div class="d-flex justify-content-between align-items-center">
                @if ($order->status === 'paid')
                    <span class="badge bg-success">LUNAS</span>
                    <a href="{{ route('eticket.show', $order->id) }}" class="btn btn-dark btn-sm">LIHAT E-TICKET</a>
                @elseif ($order->status === 'pending')
                    <span class="badge bg-warning text-dark">MENUNGGU PEMBAYARAN</span>
                    <a href="{{ route('payment.show', $order->checkout_group_id) }}" class="btn btn-dark btn-sm">LANJUT BAYAR</a>
                @elseif ($order->status === 'cancelled')
                    <span class="badge bg-secondary">DIBATALKAN</span>
                @elseif ($order->status === 'expired')
                    <span class="badge bg-secondary">EXPIRED</span>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">Kamu belum punya pesanan tiket.</p>
    @endforelse

</div>
@endsection