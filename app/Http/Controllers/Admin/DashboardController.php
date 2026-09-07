<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tour;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPesanan = Order::count();
        $menungguBayar = Order::where('status', 'pending')->count();
        $lunas = Order::where('status', 'paid')->count();
        $dibatalkan = Order::where('status', 'cancelled')->count();
        $kedaluwarsa = Order::where('status', 'expired')->count();

        $tours = Tour::with('artist')->get();

        $penjualanPerTour = $tours->map(function ($tour) {
            $totalTerjual = Order::where('status', 'paid')
                ->whereHas('ticketTier.jadwal', function ($query) use ($tour) {
                    $query->where('tour_id', $tour->id);
                })
                ->sum('jumlah_tiket');

            return [
                'nama_artist' => $tour->artist->nama_grup,
                'total_terjual' => $totalTerjual,
            ];
        });

        $maxTerjual = $penjualanPerTour->max('total_terjual') ?: 1;

        $riwayatPembelian = Order::where('status', 'paid')
            ->with('user', 'ticketTier.jadwal.tour.artist')
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalPesanan',
            'menungguBayar',
            'lunas',
            'dibatalkan',
            'kedaluwarsa',
            'penjualanPerTour',
            'maxTerjual',
            'riwayatPembelian'
        ));
    }
}
