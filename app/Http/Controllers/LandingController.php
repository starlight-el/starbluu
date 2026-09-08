<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;

class LandingController extends Controller
{
    public function index()
    {
        $tours = Tour::with(['artist', 'jadwals'])->get();

        $tourList = $tours->where('kategori', 'tour');
        $worldTourList = $tours->where('kategori', 'world_tour');

        return view('landing.index', compact('tours', 'tourList', 'worldTourList'));
    }
}
