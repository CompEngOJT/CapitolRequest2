<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder {
    public function run(): void {
        DB::table('admin_users')->insert([
            'username' => 'ADMIN',
            'password' => Hash::make('123'), // Encrypt password
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
