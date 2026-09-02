<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EticketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(int $orderId)
    {
        $order = Order::with('ticketTier.jadwal.tour.artist', 'tickets', 'user')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order || $order->status !== 'paid') {
            abort(404);
        }

        return view('eticket.show', compact('order'));
    }
}
