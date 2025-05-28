<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reservation = Reservation::factory()->create();
        
        return [
            'reservationId' => $reservation->id,
            'invoiceNumber' => 'INV-' . date('Y') . '-' . str_pad($this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'amount' => $reservation->package->price,
            'status' => $this->faker->randomElement(['paid', 'unpaid']),
            'dueDate' => now()->addDays(14)
        ];
    }
}
