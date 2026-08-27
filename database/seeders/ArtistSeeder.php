<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artist::create([
            'nama_grup' => 'aespa',
            'foto_thumbnail' => 'aespa.jpg',
            'deskripsi' => 'Girl group K-pop di bawah naungan SM Entertainment.',
        ]);
        
        Artist::create([
            'nama_grup' => 'CORTIS',
            'foto_thumbnail' => 'cortis.jpg',
            'deskripsi' => 'Boy group K-pop terbaru di bawah naungan BigHit Music (HYBE Labels).',
        ]);
        
        Artist::create([
            'nama_grup' => 'Stray Kids',
            'foto_thumbnail' => 'straykids.jpg',
            'deskripsi' => 'Boy group K-pop di bawah naungan JYP Entertainment.',
        ]);

        Artist::create([
            'nama_grup' => 'BABYMONSTER',
            'foto_thumbnail' => 'babymonster.jpg',
            'deskripsi' => 'Girl group K-pop di bawah naungan YG Entertainment.',
        ]);

    }
}
