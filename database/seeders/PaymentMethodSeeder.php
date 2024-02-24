<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_methods = [
            ["name" => "Credit Card"],
            ["name" => "Cash On Delivery"],
        ];

        PaymentMethod::insert($payment_methods);
    }
}
