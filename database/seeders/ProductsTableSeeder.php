<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $productList = [
            [
                'product_name' => 'Engine Oil',
                'total_stocks' => 2200,
                'last_withdrawal' => 0,
                'stock_remaining' => 2200
            ],
            [
                'product_name' => 'Gear Oil',
                'total_stocks' => 8000,
                'last_withdrawal' => 0,
                'stock_remaining' => 8000
            ],
            [
                'product_name' => 'Hydraulic Oil',
                'total_stocks' => 1000,
                'last_withdrawal' => 0,
                'stock_remaining' => 1000
            ],
            [
                'product_name' => 'ATF',
                'total_stocks' => 800,
                'last_withdrawal' => 0,
                'stock_remaining' => 800
            ],
            [
                'product_name' => 'Fuel',
                'total_stocks' => 5000,
                'last_withdrawal' => 0,
                'stock_remaining' => 5000
            ],
            [
                'product_name' => 'Coolant',
                'total_stocks' => 23,
                'last_withdrawal' => 0,
                'stock_remaining' => 23
            ],
            [
                'product_name' => 'Grease',
                'total_stocks' => 150,
                'last_withdrawal' => 0,
                'stock_remaining' => 150
            ],
            [
                'product_name' => 'Brake Fluid',
                'total_stocks' => 23,
                'last_withdrawal' => 0,
                'stock_remaining' =>23
            ],
            [
                'product_name' => 'Brake Cleaner',
                'total_stocks' => 52,
                'last_withdrawal' => 0,
                'stock_remaining' => 52
            ],
            [
                'product_name' => 'WD40',
                'total_stocks' => 23,
                'last_withdrawal' => 0,
                'stock_remaining' => 23
            ],
            
        ];

        foreach ($productList as $product) {
            DB::table('inventory_products')->insert([
                'product_name' => $product['product_name'],
                'total_stocks' => $product['total_stocks'],
                'last_withdrawal' => $product['last_withdrawal'],
                'stock_remaining' => $product['stock_remaining'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}