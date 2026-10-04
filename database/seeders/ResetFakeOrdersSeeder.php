<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetFakeOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $sisaKuota = DB::table('orders')
            ->whereIn('status', ['paid', 'pending'])
            ->select('ticket_tier_id', DB::raw('SUM(jumlah_tiket) as total'))
            ->groupBy('ticket_tier_id')
            ->get();

        foreach ($sisaKuota as $baris) {
            DB::table('ticket_tiers')
                ->where('id', $baris->ticket_tier_id)
                ->increment('kuota', $baris->total);
        }

        $totalOrderDihapus = DB::table('orders')->count();

        DB::table('tickets')->delete();
        DB::table('orders')->delete();

        $this->command->info("Kuota dipulihkan untuk " . $sisaKuota->count() . " tier, $totalOrderDihapus order lama dihapus.");
    }
}