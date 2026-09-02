<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(string $checkoutGroupId)
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
            return redirect()->route('tickets.index')->with('error', 'Pesanan ini sudah tidak berlaku.');
        }

        $totalBayar = $orders->sum('total_harga');
        $expiredAt = $orders->first()->expired_at;

        return view('payment.show', compact('orders', 'totalBayar', 'expiredAt', 'checkoutGroupId'));
    }

    public function process(Request $request, string $checkoutGroupId)
    {
        $request->validate([
            'kategori_pembayaran' => 'required|in:transfer_bank,e_wallet',
            'bank' => 'required_if:kategori_pembayaran,transfer_bank|in:BCA,BNI,Mandiri',
            'e_wallet' => 'required_if:kategori_pembayaran,e_wallet|in:GoPay,OVO,DANA',
        ]);

        $orders = Order::with('ticketTier.jadwal.tour.artist')
            ->where('checkout_group_id', $checkoutGroupId)
            ->where('user_id', Auth::id())
            ->get();

        if ($orders->isEmpty()) {
            abort(404);
        }

        $this->batalkanJikaKedaluwarsa($orders);

        if ($orders->first()->status !== 'pending') {
            return redirect()->route('tickets.index')->with('error', 'Pesanan ini sudah tidak berlaku.');
        }

        $metodePembayaran = $request->kategori_pembayaran === 'transfer_bank'
            ? $request->bank
            : $request->e_wallet;

        DB::transaction(function () use ($orders, $metodePembayaran) {
            foreach ($orders as $order) {
                $order->update([
                    'status' => 'paid',
                    'metode_pembayaran' => $metodePembayaran,
                ]);

                $namaArtist = $order->ticketTier->jadwal->tour->artist->nama_grup;

                for ($i = 0; $i < $order->jumlah_tiket; $i++) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'kode_eticket' => Ticket::generateKodeETicket($namaArtist),
                    ]);
                }
            }
        });

        return redirect()->route('tickets.index')->with('info', 'Pembayaran berhasil, tiket kamu sudah lunas.');
    }

    private function batalkanJikaKedaluwarsa(\Illuminate\Support\Collection $orders)
    {
        foreach ($orders as $order) {
            if ($order->status === 'pending' && $order->expired_at && $order->expired_at->isPast()) {
                $order->ticketTier()->increment('kuota', $order->jumlah_tiket);
                $order->update(['status' => 'expired']);
            }
        }
    }
}