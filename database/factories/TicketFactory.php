<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_eticket' => 'SB-' . strtoupper(Str::random(8)) . '-' . $this->faker->unique()->numberBetween(100000, 999999),
        ];
    }
}
