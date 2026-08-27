<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;
use App\Models\Tour;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aespa = Artist::where('nama_grup', 'aespa')->first();

        Tour::create([
            'artist_id' => $aespa->id,
            'nama_tour' => 'aespa LIVE TOUR - SYNK : COMPLæXITY',
            'kategori' => 'tour',
            'foto_banner_home' => 'aespa_banner_home.jpg',
            'foto_banner_detail' => 'aespa_banner_detail.jpg',
        ]);

        $cortis = Artist::where('nama_grup', 'CORTIS')->first();

        Tour::create([
            'artist_id' => $cortis->id,
            'nama_tour' => '2026 CORTIS TOUR <PUT YOUR PHONE DOWN>',
            'kategori' => 'tour',
            'foto_banner_home' => 'cortis_banner_home.jpg',
            'foto_banner_detail' => 'cortis_banner_detail.jpg',
        ]);

        $straykids = Artist::where('nama_grup', 'Stray Kids')->first();

        Tour::create([
            'artist_id' => $straykids->id,
            'nama_tour' => 'World Tour <RUN IT>',
            'kategori' => 'world_tour',
            'foto_banner_home' => 'straykids_banner_home.jpg',
            'foto_banner_detail' => 'straykids_banner_detail.jpg',
        ]);

        $babymonster = Artist::where('nama_grup', 'BABYMONSTER')->first();

        Tour::create([
            'artist_id' => $babymonster->id,
            'nama_tour' => '2026-27 BABYMONSTER WORLD TOUR [춤 (CHOOM)]',
            'kategori' => 'world_tour',
            'foto_banner_home' => 'babymonster_banner_home.jpg',
            'foto_banner_detail' => 'babymonster_banner_detail.jpg',
        ]);
    }
}
