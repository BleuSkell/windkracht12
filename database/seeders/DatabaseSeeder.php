<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Day;
use App\Models\Time;
use App\Models\Dates;
use App\Models\Reservation;
use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        $roles = [
            Role::factory()->create(['roleName' => 'customer']),
            Role::factory()->create(['roleName' => 'instructor']),
            Role::factory()->create(['roleName' => 'owner']),
        ];

        $owner = User::factory()->create([
            'roleId' => $roles[2]->id,
            'name' => 'Owner',
            'email' => 'test@gmail.com',
            'password' => bcrypt('Password123'),
        ]);

        for ($i = 0; $i < 9; $i++) {
            User::factory()->create([
                'roleId' => $roles[array_rand([$roles[0]->id, $roles[1]->id])]
            ]);
        }

        Day::factory()->createMany([
            ['day' => 'Maandag'],
            ['day' => 'Dinsdag'],
            ['day' => 'Woensdag'],
            ['day' => 'Donderdag'],
            ['day' => 'Vrijdag'],
            ['day' => 'Zaterdag'],
            ['day' => 'Zondag']
        ]);

        Time::factory()->createMany([
            ['time' => '10:00'],
            ['time' => '11:00'],
            ['time' => '12:00'],
            ['time' => '13:00'],
            ['time' => '14:00'],
            ['time' => '15:00'],
            ['time' => '16:00'],
            ['time' => '17:00']
        ]);

        Dates::factory()->count(56)->create();

        foreach (User::all() as $user) {
            // Randomly select a date for the reservation
            $date = Dates::inRandomOrder()->first();
            
            // Generate a payment for the reservation
            $payment = Payment::factory()->create();

            // Create a reservation linked to the user and date
            Reservation::factory()->create([
                'userId' => $user->id,
                'dateId' => $date->id,
                'paymentId' => $payment->id,
            ]);
        }
    }
}
