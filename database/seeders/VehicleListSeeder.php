<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleListSeeder extends Seeder {
    public function run(): void {
        $vehicles = [
            ['equipment_type' => 'DAEWOO DUMPTRUCK', 'unit' => '52-DT-61'],
            ['equipment_type' => 'ISUZU DUMPTRUCK', 'unit' => '52-DT-63'],
            ['equipment_type' => 'XCMG DUMPTRUCK', 'unit' => '52-DT-66'],
            ['equipment_type' => 'XCMG GRADER', 'unit' => '52-MG-32'],
            ['equipment_type' => 'MOTOR GRADER', 'unit' => '52-MG-33'],
            ['equipment_type' => 'XCMG MOTOR GRADER', 'unit' => '52-MG-36'],
            ['equipment_type' => 'BACKHOE', 'unit' => '52-BH-7'],
            ['equipment_type' => 'XCMG BACKHOE', 'unit' => '52-BH-8'],
            ['equipment_type' => 'BULLDOZER', 'unit' => '52-DZ-14'],
            ['equipment_type' => 'XCMG ROAD ROLLER', 'unit' => '52-SR-13'],
            ['equipment_type' => 'XCMG PAYLOADER', 'unit' => '52-FL-15'],
            ['equipment_type' => 'FOTON BOOMTRUCK', 'unit' => '52-BT-1'],
            ['equipment_type' => 'XCMG PRIME MOVER', 'unit' => '52-PM-6'],
        ];

        foreach ($vehicles as $vehicle) {
            DB::table('vehicle_list_table')->insert([
                'equipment_type' => $vehicle['equipment_type'],
                'unit' => $vehicle['unit'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
