<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Ticket;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        $orders = Order::factory(15)->create();

        $counters = [];

        foreach ($orders as $order) {
            if ($order->status === 'paid') {
                $namaArtist = $order->ticketTier->jadwal->tour->artist->nama_grup;
                $kodeArtist = strtoupper(str_replace(' ', '', $namaArtist));

                for ($i = 0; $i < $order->jumlah_tiket; $i++) {
                    $counters[$kodeArtist] = ($counters[$kodeArtist] ?? 0) + 1;
                    $nomorUrut = str_pad($counters[$kodeArtist], 6, '0', STR_PAD_LEFT);

                    Ticket::factory()->create([
                        'order_id' => $order->id,
                        'kode_eticket' => "SB-{$kodeArtist}-{$nomorUrut}",
                    ]);
                }
            }
        }
    }
}
