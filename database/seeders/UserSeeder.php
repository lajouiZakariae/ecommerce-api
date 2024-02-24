<?php

namespace Database\Seeders;

use App\Enums\Role as EnumsRole;
use App\Models\Role;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ["name" => "admin"],
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
            'first_name' => 'Ilham',
            'last_name' => 'El Maimouni',
            'role_id' => EnumsRole::SALES_ASSISTANT->value,
            'email' => 'ilhammaimouni269@gmail.com',
            'password' => Hash::make('1234')
        ]);
    }
}
