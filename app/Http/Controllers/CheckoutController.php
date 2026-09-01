<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tiers' => 'required|array',
        ]);

        $jadwal = Jadwal::with('ticketTiers')->findOrFail($request->jadwal_id);

        $tiersDipilih = collect($request->tiers)->filter(fn ($jumlah) => (int) $jumlah > 0);

        if ($tiersDipilih->isEmpty()) {
            return back()->with('error', 'Pilih minimal 1 tiket terlebih dahulu.');
        }

        $totalTiketDiminta = $tiersDipilih->sum(fn ($jumlah) => (int) $jumlah);

        $totalTiketAktifUser = Order::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'paid'])
            ->sum('jumlah_tiket');

        if (($totalTiketAktifUser + $totalTiketDiminta) > 2) {
            return back()->with('error', 'Maksimal 2 tiket per akun. Kamu sudah punya ' . $totalTiketAktifUser . ' tiket aktif.');
        }

        foreach ($tiersDipilih as $tierId => $jumlah) {
            $tier = $jadwal->ticketTiers->firstWhere('id', (int) $tierId);

            if (!$tier || $tier->kuota < $jumlah) {
                return back()->with('error', 'Kuota tidak mencukupi untuk salah satu tier yang dipilih.');
            }
        }

        $checkoutGroupId = (string) Str::uuid();
        $expiredAt = now()->addMinutes(config('starbluu.checkout_expiry_minutes'));

        DB::transaction(function () use ($tiersDipilih, $jadwal, $checkoutGroupId, $expiredAt) {
            foreach ($tiersDipilih as $tierId => $jumlah) {
                $tier = $jadwal->ticketTiers->firstWhere('id', (int) $tierId);

                $tier->decrement('kuota', $jumlah);

                Order::create([
                    'user_id' => Auth::id(),
                    'checkout_group_id' => $checkoutGroupId,
                    'ticket_tier_id' => $tier->id,
                    'jumlah_tiket' => $jumlah,
                    'total_harga' => $tier->harga * $jumlah,
                    'status' => 'pending',
                    'expired_at' => $expiredAt,
                ]);
            }
        });

        return redirect()->route('checkout.show', ['checkoutGroupId' => $checkoutGroupId]);
    }

    public function show($checkoutGroupId)
    {
        $orders = Order::with('ticketTier.jadwal.tour.artist')
            ->where('checkout_group_id', $checkoutGroupId)
            ->where('user_id', Auth::id())
            ->get();

        if ($orders->isEmpty()) {
            abort(404);
        }

        $this->batalkanJikaKedaluwarsa($orders);

        if ($orders->first()->status !== 'pending') {
            return redirect()->route('landing')->with('info', 'Pesanan ini sudah tidak berlaku.');
        }

        $totalBayar = $orders->sum('total_harga');
        $expiredAt = $orders->first()->expired_at;

        return view('checkout.show', compact('orders', 'totalBayar', 'expiredAt', 'checkoutGroupId'));
    }

    public function cancel($checkoutGroupId)
    {
        $orders = Order::where('checkout_group_id', $checkoutGroupId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        foreach ($orders as $order) {
            $order->ticketTier()->increment('kuota', $order->jumlah_tiket);
            $order->update(['status' => 'cancelled']);
        }

        return redirect()->route('landing')->with('info', 'Pesanan berhasil dibatalkan.');
    }

    private function batalkanJikaKedaluwarsa($orders)
    {
        foreach ($orders as $order) {
            if ($order->status === 'pending' && $order->expired_at && $order->expired_at->isPast()) {
                $order->ticketTier()->increment('kuota', $order->jumlah_tiket);
                $order->update(['status' => 'expired']);
            }
        }
    }
}
