<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class Instructor_customerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'instructorId' => Instructor::factory(),
            'customerId' => Customer::factory(),
        ];
    }
}