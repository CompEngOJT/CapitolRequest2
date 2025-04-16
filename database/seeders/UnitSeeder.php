<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder {
    public function run(): void {
        $units = [
            '52-DT-61', '52-DT-63', '52-DT-66', '52-DT-72', '52-DT-74', '52-DT-75',
            '52-DT-77', '52-DT-78', '52-DT-79', '52-DT-81', '52-DT-82', '52-DT-83',
            '52-DT-85', '52-MG-32', '52-MG-33', '52-MG-36', '52-MG-37', '52-MG-38',
            '52-MG-39', '52-BH-7', '52-BH-12', '52-BH-9', '52-BH-15', '52-BH-14',
            '52-BH-8', '52-BH-11', '52-BH-20', '52-BH-21', '52-BH-13', '52-BH-16',
            '52-BH-10', '52-BH-19', '52-BH-18', '52-DZ-14', '52-SR-14', '52-SR-15',
            '52-SR-16', '52-SR-13', '52-FL-15', '52-FL-16', '52-FL-17', '52-FL-20',
            '52-FL-21', '52-FL-22', '52-BT-1', '52-PM-6'
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert([
                'name' => $unit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
