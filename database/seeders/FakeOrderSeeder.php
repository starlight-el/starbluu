<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FakeOrderSeeder extends Seeder
{
    private array $rentangOrder = [
        'aespa' => [3, 4],
        'babymonster' => [3, 4],
        'stray kids' => [3, 4],
        'enhypen' => [1, 2],
    ];

    private array $rentangDefault = [3, 3];

    private array $metodePembayaran = ['BCA', 'BNI', 'Mandiri', 'GoPay', 'OVO', 'DANA'];

    public function run(): void
    {
        $customerIds = DB::table('users')
            ->where('role', 'user')
            ->where('email', 'like', '%@mail.com')
            ->pluck('id')
            ->all();

        if (count($customerIds) < 20) {
            $this->command->error('Customer fake kurang. Jalankan FakeCustomerSeeder dulu.');
            return;
        }

        $statusPool = array_merge(
            array_fill(0, 80, 'paid'),
            array_fill(0, 10, 'cancelled'),
            array_fill(0, 10, 'expired')
        );

        $menitExpiry = (int) config('starbluu.checkout_expiry_minutes', 10);
        $totalOrder = 0;
        $totalTiket = 0;
        $perArtist = [];

        $jadwals = Jadwal::with(['ticketTiers', 'tour.artist'])->get();

        DB::transaction(function () use ($jadwals, $customerIds, $statusPool, $menitExpiry, &$totalOrder, &$totalTiket, &$perArtist) {
            foreach ($jadwals as $jadwal) {
                if ($jadwal->ticketTiers->isEmpty()) {
                    continue;
                }

                $namaArtist = $jadwal->tour->artist->nama_grup;
                [$min, $maks] = $this->rentangOrder[strtolower($namaArtist)] ?? $this->rentangDefault;

                $jumlahPembeli = min(rand($min, $maks), count($customerIds));
                $pembeli = collect($customerIds)->random($jumlahPembeli)->all();

                foreach ($pembeli as $userId) {
                    $tiersTersedia = $jadwal->ticketTiers->filter(fn ($t) => $t->kuota > 0);

                    if ($tiersTersedia->isEmpty()) {
                        break;
                    }

                    $tier = $tiersTersedia->random();
                    $jumlah = min(rand(1, 2), $tier->kuota);
                    $status = $statusPool[array_rand($statusPool)];

                    $dibuat = now()
                        ->subDays(rand(1, 25))
                        ->setTime(rand(8, 23), rand(0, 59), rand(0, 59));

                    $order = new Order();
                    $order->forceFill([
                        'user_id' => $userId,
                        'checkout_group_id' => (string) Str::uuid(),
                        'ticket_tier_id' => $tier->id,
                        'jumlah_tiket' => $jumlah,
                        'total_harga' => $tier->harga * $jumlah,
                        'status' => $status,
                        'metode_pembayaran' => $status === 'paid'
                            ? $this->metodePembayaran[array_rand($this->metodePembayaran)]
                            : null,
                        'expired_at' => $dibuat->copy()->addMinutes($menitExpiry),
                        'created_at' => $dibuat,
                        'updated_at' => $dibuat,
                    ])->save();

                    if (in_array($status, ['paid', 'pending'])) {
                        $tier->decrement('kuota', $jumlah);
                    }

                    $totalOrder++;
                    $perArtist[$namaArtist] = ($perArtist[$namaArtist] ?? 0) + 1;

                    if ($status === 'paid') {
                        for ($i = 0; $i < $jumlah; $i++) {
                            $checkIn = null;

                            if (rand(1, 100) <= 40) {
                                $checkIn = $dibuat->copy()->addHours(rand(1, 72));
                                if ($checkIn->isFuture()) {
                                    $checkIn = now();
                                }
                            }

                            $ticket = new Ticket();
                            $ticket->forceFill([
                                'order_id' => $order->id,
                                'kode_eticket' => Ticket::generateKodeETicket($namaArtist),
                                'checked_in_at' => $checkIn,
                                'created_at' => $dibuat,
                                'updated_at' => $dibuat,
                            ])->save();

                            $totalTiket++;
                        }
                    }
                }
            }
        });

        $this->command->info("$totalOrder order dan $totalTiket tiket berhasil dibuat.");

        arsort($perArtist);
        foreach ($perArtist as $nama => $jumlah) {
            $this->command->line("  $nama: $jumlah order");
        }
    }
}