<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\TicketTier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tier = TicketTier::inRandomOrder()->first();
        $user = User::where('role', 'user')->inRandomOrder()->first();
        $jumlahTiket = $this->faker->numberBetween(1, 2);
        $status = $this->faker->randomElement(['paid', 'paid', 'paid', 'pending', 'cancelled', 'expired']);

        return [
            'user_id' => $user->id,
            'ticket_tier_id' => $tier->id,
            'jumlah_tiket' => $jumlahTiket,
            'total_harga' => $tier->harga * $jumlahTiket,
            'status' => $status,
            'metode_pembayaran' => $status === 'pending' ? null : $this->faker->randomElement(['BCA', 'BNI', 'Mandiri', 'GoPay', 'DANA']),
            'expired_at' => $status === 'pending' ? now()->addMinutes(10) : null,
        ];
    }
}
