<?php

namespace Database\Seeders;

use App\Models\Supplier;
use File;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = collect(json_decode(File::get(base_path('json/suppliers.json'))));

        $suppliers->each(function (object $supplier) {
            Supplier::create((array) $supplier);
        });
    }
}
