<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ArtistSeeder::class,
            ArtistMemberSeeder::class,
            TourSeeder::class,
            JadwalSeeder::class,
            TicketTierSeeder::class,
            AdminSeeder::class,
            OrderSeeder::class,
        ]);
    }
}