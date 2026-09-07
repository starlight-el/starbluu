@extends('layouts.admin')

@section('content')
    <h3 class="fw-bold mb-4">Dashboard & Pesanan</h3>

    <div class="row row-cols-1 row-cols-md-5 g-3 mb-5">
        <div class="col">
            <div class="border rounded p-3 text-center h-100">
                <p class="text-muted mb-1">Total Pesanan</p>
                <h4 class="fw-bold mb-0">{{ $totalPesanan }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="border rounded p-3 text-center h-100">
                <p class="text-muted mb-1">Menunggu Bayar</p>
                <h4 class="fw-bold mb-0">{{ $menungguBayar }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="border rounded p-3 text-center h-100">
                <p class="text-muted mb-1">Lunas</p>
                <h4 class="fw-bold mb-0">{{ $lunas }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="border rounded p-3 text-center h-100">
                <p class="text-muted mb-1">Dibatalkan</p>
                <h4 class="fw-bold mb-0">{{ $dibatalkan }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="border rounded p-3 text-center h-100">
                <p class="text-muted mb-1">Kedaluwarsa</p>
                <h4 class="fw-bold mb-0">{{ $kedaluwarsa }}</h4>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3">Riwayat Pembelian Terbaru</h5>

    <div class="table-responsive mb-5">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tier</th>
                    <th>Artist / Tour</th>
                    <th>Jadwal</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatPembelian as $order)
                    <tr>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->ticketTier->nama_tier }}</td>
                        <td>
                            {{ $order->ticketTier->jadwal->tour->artist->nama_grup }}<br>
                            <span class="text-muted" style="font-size: 0.85rem;">{{ $order->ticketTier->jadwal->tour->nama_tour }}</span>
                        </td>
                        <td>
                            {{ $order->ticketTier->jadwal->kota }}<br>
                            <span class="text-muted" style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($order->ticketTier->jadwal->tanggal)->format('d M Y') }}</span>
                        </td>
                        <td>{{ $order->jumlah_tiket }}</td>
                        <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pembelian yang lunas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h5 class="fw-bold mb-3">Penjualan Tiket per Tour</h5>

    <div class="d-flex align-items-end" style="height: 220px; gap: 24px;">
        @foreach ($penjualanPerTour as $item)
            @php
                $tinggiPersen = $maxTerjual > 0 ? ($item['total_terjual'] / $maxTerjual) * 100 : 0;
            @endphp
            <div class="d-flex flex-column align-items-center" style="flex: 1;">
                <div class="w-100 bg-light border" style="height: 160px; display: flex; align-items: flex-end;">
                    <div class="w-100 bg-secondary" style="height: {{ $tinggiPersen }}%;"></div>
                </div>
                <p class="mt-2 mb-0 text-center" style="font-size: 0.85rem;">{{ $item['nama_artist'] }}</p>
                <p class="mb-0 fw-bold" style="font-size: 0.85rem;">{{ $item['total_terjual'] }}</p>
            </div>
        @endforeach
    </div>
@endsection