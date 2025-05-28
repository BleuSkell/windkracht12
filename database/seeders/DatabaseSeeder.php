<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Contact;
use App\Models\Package;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\Invoice;

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
            'email' => 'test@gmail.com',
            'password' => bcrypt('Password123'),
        ]);
        
        Contact::factory()->create(['userId' => $owner->id]);

        for ($i = 0; $i < 9; $i++) {
            $user = User::factory()->create([
            'roleId' => $roles[array_rand([$roles[0]->id, $roles[1]->id])]
            ]);
            Contact::factory()->create(['userId' => $user->id]);
        }

        // Create packages
        Package::factory()->count(5)->create();

        // Create locations
        Location::factory()->count(3)->create();

        // Create reservations with invoices
        Reservation::factory()
            ->count(20)
            ->create()
            ->each(function ($reservation) {
                Invoice::factory()->create([
                    'reservationId' => $reservation->id,
                    'amount' => $reservation->package->price
                ]);
            });
    }
}
