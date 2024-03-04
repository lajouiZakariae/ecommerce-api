<?php

namespace Database\Seeders;

use App\Enums\Role as EnumsRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ["name" => "admin"],
            ["name" => "products_manager"],
            ["name" => "sales_assistant"],
        ]);

        User::factory()->create([
            'first_name' => 'Zakariae',
            'last_name' => 'Lajoui',
            'role_id' => EnumsRole::ADMIN->value,
            'email' => 'lajoui.zakariae.1@gmail.com',
            'password' => Hash::make('1234')
        ]);

        User::factory()->create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'role_id' => EnumsRole::PRODUCTS_MANAGER->value,
            'email' => fake()->email(),
            'password' => Hash::make('1234')
        ]);

        User::factory()->create([
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'role_id' => EnumsRole::SALES_ASSISTANT->value,
            'email' => fake()->email(),
            'password' => Hash::make('1234')
        ]);

        // User::factory()->create([
        //     'first_name' => 'Ilham',
        //     'last_name' => 'El Maimouni',
        //     'role_id' => EnumsRole::SALES_ASSISTANT->value,
        //     'email' => 'ilhammaimouni269@gmail.com',
        //     'password' => Hash::make('1234')
        // ]);

    }
}
