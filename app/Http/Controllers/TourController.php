<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::with('artist')->where('kategori', 'tour')->get();

        return view('tour.index', compact('tours'));
    }

    public function worldTour()
    {
        $tours = Tour::with('artist')->where('kategori', 'world_tour')->get();

        return view('tour.world', compact('tours'));
    }
}
