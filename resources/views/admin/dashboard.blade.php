@extends('layouts.admin')

@section('content')
    <div class="dash-page">

        <h3 class="fw-bold mb-4 dash-heading">Dashboard & Pesanan</h3>

        <div class="row row-cols-1 row-cols-md-5 g-4 mb-4 dash-stats">
            <div class="col">
                <div class="dash-stat-card p-3 text-center h-100">
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Total Pesanan</p>
                    <h4 class="fw-bold mb-0 text-white">{{ $totalPesanan }}</h4>
                </div>
            </div>
            <div class="col">
                <div class="dash-stat-card p-3 text-center h-100">
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Menunggu Bayar</p>
                    <h4 class="fw-bold mb-0 text-white">{{ $menungguBayar }}</h4>
                </div>
            </div>
            <div class="col">
                <div class="dash-stat-card p-3 text-center h-100">
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Lunas</p>
                    <h4 class="fw-bold mb-0 text-white">{{ $lunas }}</h4>
                </div>
            </div>
            <div class="col">
                <div class="dash-stat-card p-3 text-center h-100">
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Dibatalkan</p>
                    <h4 class="fw-bold mb-0 text-white">{{ $dibatalkan }}</h4>
                </div>
            </div>
            <div class="col">
                <div class="dash-stat-card p-3 text-center h-100">
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Kedaluwarsa</p>
                    <h4 class="fw-bold mb-0 text-white">{{ $kedaluwarsa }}</h4>
                </div>
            </div>
        </div>

        <div class="dash-row">

            <div class="dash-chart-card">
                <h6 class="dash-section-title">Penjualan Tiket per Tour</h6>

                <div class="dash-chart-container">
                    @foreach ($penjualanPerTour as $item)
                        @php
                            $tinggiPersen = $maxTerjual > 0 ? ($item['total_terjual'] / $maxTerjual) * 100 : 0;
                        @endphp
                        <div class="dash-chart-column">
                            <div class="dash-bar-wrap">
                                <span class="dash-bar-value">{{ $item['total_terjual'] }}</span>
                                <div class="dash-bar" style="height: {{ max($tinggiPersen, 4) }}%;" title="{{ $item['nama_artist'] }}"></div>
                            </div>
                            <span class="dash-chart-label">{{ $item['nama_artist'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="dash-table-card">
                <h6 class="dash-section-title">Riwayat Pembelian Terbaru</h6>

                <div class="dash-table-wrap">
                    <table class="table align-middle mb-0 dash-table">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 13%;">
                            <col style="width: 22%;">
                            <col style="width: 18%;">
                            <col style="width: 7%;">
                            <col style="width: 25%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tier</th>
                                <th>Artist / Tour</th>
                                <th>Jadwal</th>
                                <th class="text-center">Jml</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatPembelian as $order)
                                <tr>
                                    <td><div class="dash-table-line">{{ $order->user->name }}</div></td>
                                    <td><div class="dash-table-line">{{ $order->ticketTier->nama_tier }}</div></td>
                                    <td>
                                        <div class="dash-table-line text-white">{{ $order->ticketTier->jadwal->tour->artist->nama_grup }}</div>
                                        <div class="dash-table-line text-muted" style="font-size: 0.75rem;">{{ $order->ticketTier->jadwal->tour->nama_tour }}</div>
                                    </td>
                                    <td>
                                        <div class="dash-table-line text-white">{{ $order->ticketTier->jadwal->kota }}</div>
                                        <div class="dash-table-line text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($order->ticketTier->jadwal->tanggal)->format('d M Y') }}</div>
                                    </td>
                                    <td class="text-center">{{ $order->jumlah_tiket }}</td>
                                    <td class="text-end"><div class="dash-table-line price-col text-white text-end">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada pembelian yang lunas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection