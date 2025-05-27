<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'infix' => $this->faker->optional()->word(),
            'lastName' => $this->faker->lastName(),
            'adress' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'dateOfBirth' => $this->faker->date(),
            'bsnNumber' => $this->faker->unique()->numerify('#########'),
            'mobile' => $this->faker->unique()->phoneNumber(),
        ];
    }
}
