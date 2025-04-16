<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder {
    public function run(): void {
        $equipmentList = [
            ['name' => 'DAEWOO DUMPTRUCK'],
            ['name' => 'ISUZU DUMPTRUCK'],
            ['name' => 'XCMG DUMPTRUCK'],
            ['name' => 'XCMG GRADER'],
            ['name' => 'MOTOR GRADER'],
            ['name' => 'XCMG MOTOR GRADER'],
            ['name' => 'BACKHOE'],
            ['name' => 'XCMG BACKHOE'],
            ['name' => 'BULLDOZER'],
            ['name' => 'XCMG ROAD ROLLER'],
            ['name' => 'XCMG PAYLOADER'],
            ['name' => 'FOTON BOOMTRUCK'],
            ['name' => 'XCMG PRIME MOVER'],
        ];

        foreach ($equipmentList as $equipment) {
            DB::table('equipment')->insert([
                'name' => $equipment['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
