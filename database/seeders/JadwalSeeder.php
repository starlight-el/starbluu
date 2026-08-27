<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\Jadwal;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tourAespa = Tour::where('nama_tour', 'like', '%aespa%')->first();

        Jadwal::create([
            'tour_id' => $tourAespa->id,
            'negara' => 'Korea Selatan',
            'kota' => 'Seoul',
            'venue' => 'Gocheok Sky Dome',
            'tanggal' => '2026-08-07',
            'jam' => '19:00:00',
            'timezone' => 'KST',
        ]);

        $tourCortis = Tour::where('nama_tour', 'like', '%CORTIS%')->first();

        Jadwal::create([
            'tour_id' => $tourCortis->id,
            'negara' => 'Korea Selatan',
            'kota' => 'Incheon',
            'venue' => 'Inspire Arena',
            'tanggal' => '2026-07-18',
            'jam' => '18:00:00',
            'timezone' => 'KST',
        ]);

        $tourStraykids = Tour::where('nama_tour', 'like', '%RUN IT%')->first();

        Jadwal::create([
            'tour_id' => $tourStraykids->id,
            'negara' => 'Korea Selatan',
            'kota' => 'Seoul',
            'venue' => 'KSPO Dome',
            'tanggal' => '2026-07-25',
            'jam' => '18:00:00',
            'timezone' => 'KST',
        ]);

        $tourBabymonster = Tour::where('nama_tour', 'like', '%BABYMONSTER%')->first();

        Jadwal::create([
            'tour_id' => $tourBabymonster->id,
            'negara' => 'Korea Selatan',
            'kota' => 'Seoul',
            'venue' => 'Jamsil Indoor Stadium',
            'tanggal' => '2026-06-26',
            'jam' => '20:00:00',
            'timezone' => 'KST',
        ]);
    }
}
