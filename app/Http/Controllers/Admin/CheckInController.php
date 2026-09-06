<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index()
    {
        return view('admin.checkin.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_eticket' => 'required|string',
        ]);

        $kode = trim($request->kode_eticket);

        $ticket = Ticket::with([
            'order.user',
            'order.ticketTier.jadwal.tour.artist',
        ])->where('kode_eticket', $kode)->first();

        if (!$ticket) {
            return back()->with('checkin_result', [
                'valid' => false,
                'message' => 'Kode e-ticket tidak ditemukan.',
            ]);
        }

        $order = $ticket->order;

        if ($order->status !== 'paid') {
            $pesanStatus = match ($order->status) {
                'pending' => 'Order belum dibayar (masih Menunggu Bayar).',
                'cancelled' => 'Order sudah dibatalkan.',
                'expired' => 'Order sudah kedaluwarsa.',
                default => 'Order tidak dalam status lunas.',
            };

            return back()->with('checkin_result', [
                'valid' => false,
                'message' => $pesanStatus,
            ]);
        }

        if ($ticket->checked_in_at) {
            return back()->with('checkin_result', [
                'valid' => false,
                'message' => 'Tiket ini sudah check-in sebelumnya pada ' . $ticket->checked_in_at->format('d M Y, H:i') . '.',
            ]);
        }

        $ticket->update(['checked_in_at' => now()]);

        $jadwal = $order->ticketTier->jadwal;
        $tour = $jadwal->tour;
        $artist = $tour->artist;

        return back()->with('checkin_result', [
            'valid' => true,
            'nama_tier' => $order->ticketTier->nama_tier,
            'nama_customer' => $order->user->name,
            'nama_artist' => $artist->nama_grup,
            'nama_tour' => $tour->nama_tour,
            'kota' => $jadwal->kota,
            'venue' => $jadwal->venue,
            'tanggal' => \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y'),
            'jam' => $jadwal->jam ? substr($jadwal->jam, 0, 5) : null,
            'timezone' => $jadwal->timezone,
        ]);
    }
}