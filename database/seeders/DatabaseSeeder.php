<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        $roles = [
            Role::factory()->create(['roleName' => 'customer']),
            Role::factory()->create(['roleName' => 'employee']),
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
    }
}
