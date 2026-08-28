<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller
{
    public function show(int $id) 
    {
        $artist = Artist::with(['artistMembers', 'tours.jadwals.ticketTiers'])->findOrFail($id);

        return view('artist.show', compact('artist'));
    }
}
