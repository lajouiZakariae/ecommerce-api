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

        User::factory()->create([ // 1
            'first_name' => 'Zakariae',
            'last_name' => 'Lajoui',
            'email' => 'lajoui.zakariae.1@gmail.com',
            'role_id' => EnumsRole::ADMIN->value,
            'password' => Hash::make('1234')
        ]);

        User::factory()->create([ // 2
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'role_id' => EnumsRole::PRODUCTS_MANAGER->value,
            'email' => fake()->email(),
            'password' => Hash::make('1234')
        ]);

        User::factory()->create([ // 3
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email(),
            'role_id' => EnumsRole::SALES_ASSISTANT->value,
            'password' => Hash::make('1234')
        ]);
    }
}
