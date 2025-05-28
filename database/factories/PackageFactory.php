<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isDuo = $this->faker->boolean(30);
        return [
            'name' => $this->faker->randomElement(['Basis', 'Pro', 'Premium']) . ' ' . ($isDuo ? 'Duo' : 'Solo') . ' Pakket',
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 200, 1000),
            'numberOfLessons' => $this->faker->randomElement([3, 5, 8]),
            'isDuo' => $isDuo
        ];
    }
}
