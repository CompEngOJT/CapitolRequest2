<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChiefsTableSeeder extends Seeder
{
    public function run()
    {
        // Insert the initial chief (Admin)
        DB::table('chiefs')->insert([
            'username' => 'Admin',
            'password' => Hash::make('12345678'), // Hash the password
            'theme' => 'light', // Default theme
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
