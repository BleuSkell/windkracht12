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
use App\Models\Instructor;
use App\Models\Customer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        // Create roles
        $roles = [
            Role::factory()->create(['roleName' => 'customer']),
            Role::factory()->create(['roleName' => 'instructor']),
            Role::factory()->create(['roleName' => 'owner']),
        ];

        // Create owner
        $owner = User::factory()->create([
            'roleId' => $roles[2]->id,
            'email' => 'test@gmail.com',
            'password' => bcrypt('Password123'),
        ]);
        Contact::factory()->create(['userId' => $owner->id]);

        // Create instructors
        $instructors = [];
        for ($i = 0; $i < 3; $i++) {
            $user = User::factory()->create([
                'roleId' => $roles[1]->id, // instructor role
            ]);
            Contact::factory()->create(['userId' => $user->id]);
            
            $instructor = Instructor::create(['userId' => $user->id]);
            $instructors[] = $instructor;
        }

        // Create customers
        $customers = [];
        for ($i = 0; $i < 10; $i++) {
            $user = User::factory()->create([
                'roleId' => $roles[0]->id, // customer role
            ]);
            Contact::factory()->create(['userId' => $user->id]);
            
            $customer = Customer::create(['userId' => $user->id]);
            $customers[] = $customer;
            
            // Randomly assign 1-2 instructors to each customer
            $numInstructors = rand(1, 2);
            $selectedInstructors = array_rand($instructors, $numInstructors);
            if (!is_array($selectedInstructors)) {
                $selectedInstructors = [$selectedInstructors];
            }
            
            foreach ($selectedInstructors as $instructorIndex) {
                $customer->instructors()->attach($instructors[$instructorIndex]->id);
            }
        }

        // Create packages
        Package::factory()->count(5)->create();

        // Create locations
        Location::factory()->count(3)->create();

        // Create reservations with invoices for customers
        foreach ($customers as $customer) {
            $numReservations = rand(1, 3);
            for ($i = 0; $i < $numReservations; $i++) {
                $reservation = Reservation::factory()->create([
                    'userId' => $customer->userId
                ]);

                Invoice::factory()->create([
                    'reservationId' => $reservation->id,
                    'amount' => $reservation->package->price
                ]);
            }
        }
    }
}
