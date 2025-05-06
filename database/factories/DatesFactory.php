<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Day;
use App\Models\Time;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dates>
 */
class DatesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dayId' => Day::factory(),
            'timeId' => Time::factory(),
        ];
    }
}
