<?php

namespace Database\Seeders;

use App\Models\Store;
use File;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = collect(json_decode(File::get(base_path('json/stores.json'))));

        $stores->each(function (object $store) {
            Store::create((array) $store);
        });
    }
}
