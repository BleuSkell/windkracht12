<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'paymentDate' => $this->faker->dateTime(),
            'paymentMethod' => $this->faker->randomElement(['iDeal', 'Creditcard', 'PayPal']),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
        ];
    }
}
