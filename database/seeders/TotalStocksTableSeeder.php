<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TotalStocksTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            'Engine Oil',
            'Gear Oil',
            'Hydraulic Oil',
            'Automatic Transmission Fluid',
            'Fuel Grease',
            'Brake Fluid',
            'Coolant',
            'Brake Cleaner',
            'Wd40',
        ];

        foreach ($products as $product) {
            DB::table('total_stocks_table')->insert([
                'product_name' => $product,
                'total_stocks' => rand(100, 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
