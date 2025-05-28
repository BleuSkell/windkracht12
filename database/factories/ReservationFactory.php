<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Location;
use App\Models\Package;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $package = Package::inRandomOrder()->first();
        
        return [
            'userId' => User::inRandomOrder()->first()->id,
            'packageId' => $package->id,
            'locationId' => Location::inRandomOrder()->first()->id,
            'duoPartnerName' => $package->isDuo ? $this->faker->name() : null,
            'duoPartnerEmail' => $package->isDuo ? $this->faker->safeEmail() : null,
            'duoPartnerAddress' => $package->isDuo ? $this->faker->streetAddress() : null,
            'duoPartnerCity' => $package->isDuo ? $this->faker->city() : null,
            'duoPartnerPhone' => $package->isDuo ? $this->faker->phoneNumber() : null,
            'reservationDate' => $this->faker->dateTimeBetween('now', '+1 month'),
            'reservationTime' => $this->faker->time(),
            'cancellationReason' => null,
            'cancellationStatus' => null,
            'originalDate' => null,
            'originalTime' => null,
        ];
    }
}
