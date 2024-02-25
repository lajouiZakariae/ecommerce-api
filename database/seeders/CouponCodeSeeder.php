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
        $coupon_codes = [
            ["code" => "LOREM03", "amount" => 25],
            ["code" => "LOREM05", "amount" => 10],
            ["code" => "LOREM07", "amount" => 10],
            ["code" => "LOREM09", "amount" => 5],
            ["code" => "LOREM12", "amount" => 10],
            ["code" => "LOREM14", "amount" => 20],
            ["code" => "LOREM16", "amount" => 40],
            ["code" => "LOREM19", "amount" => 50],
        ];

        collect($coupon_codes)->each(function ($coupon_code) {
            CouponCode::create($coupon_code);
        });
    }
}
