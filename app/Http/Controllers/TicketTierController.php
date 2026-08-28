<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;

class TicketTierController extends Controller
{
    public function show(int $jadwalId)
    {
        $jadwal = Jadwal::with(['tour.artist', 'ticketTiers'])->findOrFail($jadwalId);

        return view('tickettier.show', compact('jadwal'));
    }
}
