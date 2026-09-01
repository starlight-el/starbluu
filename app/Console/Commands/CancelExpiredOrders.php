<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('orders:cancel-expired')]
#[Description('Batalkan otomatis semua Order pending yang sudah melewati waktu checkout dan kembalikan kuotanya')]
class CancelExpiredOrders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('status', 'pending')
            ->where('expired_at', '<', now())
            ->get();

        foreach ($orders as $order) {
            $order->ticketTier()->increment('kuota', $order->jumlah_tiket);
            $order->update(['status' => 'expired']);
        }

        $this->info($orders->count() . ' order berhasil dibatalkan otomatis.');
    }
}
