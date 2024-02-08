<?php

namespace Database\Seeders;

use App\Models\CouponCode;
use Illuminate\Database\Seeder;

class CouponCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CouponCode::factory()->count(5)->create();
    }
}
