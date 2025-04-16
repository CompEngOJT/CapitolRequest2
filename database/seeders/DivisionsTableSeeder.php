<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionsTableSeeder extends Seeder {
    public function run(): void {
        DB::table('divisions')->truncate(); // Clear existing data

        $divisions = [
            'ADMIN', 'CONSTRUCTION', 'MAINTENANCE', 'MOTORPOOL',
            'MTQC', 'PLANNING', 'SUPPLY'
        ];

        foreach ($divisions as $division) {
            DB::table('divisions')->insert([
                'name' => $division,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
