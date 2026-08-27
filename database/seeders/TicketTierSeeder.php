<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\TicketTier;

class TicketTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            ['nama_tier' => 'VIP Soundcheck', 'harga' => 7000000, 'kuota' => 50],
            ['nama_tier' => 'Floor/Standing', 'harga' => 5500000, 'kuota' => 200],
            ['nama_tier' => 'CAT 1', 'harga' => 4500000, 'kuota' => 300],
            ['nama_tier' => 'CAT 2', 'harga' => 3500000, 'kuota' => 400],
            ['nama_tier' => 'CAT 3', 'harga' => 2000000, 'kuota' => 500],
        ];

        $semuaJadwal = Jadwal::all();

        foreach ($semuaJadwal as $jadwal) {
            foreach ($tiers as $tier) {
                TicketTier::create([
                    'jadwal_id' => $jadwal->id,
                    'nama_tier' => $tier['nama_tier'],
                    'harga' => $tier['harga'],
                    'kuota' => $tier['kuota'],
                ]);
            }
        }
    }
}
