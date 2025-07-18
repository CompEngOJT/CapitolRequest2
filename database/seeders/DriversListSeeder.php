<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriversListSeeder extends Seeder {
    public function run(): void {
        $drivers = [
            ['first_name' => 'Alfredo', 'last_name' => 'Magto'],
            ['first_name' => 'Andrelo', 'last_name' => 'Apduhan'],
            ['first_name' => 'Armtemio', 'last_name' => 'Saligumba'],
            ['first_name' => 'Chris', 'last_name' => 'Ofima'],
            ['first_name' => 'Dennis', 'last_name' => 'Ofima'],
            ['first_name' => 'Edwin', 'last_name' => 'Tupaz'],
            ['first_name' => 'Elmer', 'last_name' => 'Momo'],
            ['first_name' => 'Elpedio', 'last_name' => 'Lumayag'],
            ['first_name' => 'Ernesto', 'last_name' => 'Millan'],
            ['first_name' => 'Esmer', 'last_name' => 'Lumahang'],
            ['first_name' => 'Fermin', 'last_name' => 'Mandin'],
            ['first_name' => 'Francisco', 'last_name' => 'Gallenero'],
            ['first_name' => 'Greg', 'last_name' => 'Coay'],
            ['first_name' => 'Guilberto', 'last_name' => 'Maputi'],
            ['first_name' => 'Hilario', 'last_name' => 'Ambaic'],
            ['first_name' => 'Jerome', 'last_name' => 'Pinote'],
            ['first_name' => 'Jovito', 'last_name' => 'Delada'],
            ['first_name' => 'Junathan', 'last_name' => 'Villasorda'],
            ['first_name' => 'Leopoldo', 'last_name' => 'Llagas'],
            ['first_name' => 'Lowen', 'last_name' => 'Isidro'],
            ['first_name' => 'Marven John', 'last_name' => 'Dagondon'],
            ['first_name' => 'Melanio', 'last_name' => 'Cailing'],
            ['first_name' => 'Narciso', 'last_name' => 'Baring'],
            ['first_name' => 'Nemesio', 'last_name' => 'Daniel'],
            ['first_name' => 'Nepthaly', 'last_name' => 'Besande'],
            ['first_name' => 'Perfecto', 'last_name' => 'Valerio'],
            ['first_name' => 'Ramon', 'last_name' => 'Casinto'],
            ['first_name' => 'Restito', 'last_name' => 'Dago'],
            ['first_name' => 'Rexcel', 'last_name' => 'Jalagat'],
            ['first_name' => 'Reynaldo', 'last_name' => 'Mutia'],
            ['first_name' => 'Roel', 'last_name' => 'Balasta'],
            ['first_name' => 'Rodel', 'last_name' => 'Gallenero'],
            ['first_name' => 'Rolly', 'last_name' => 'Gallardo'],
            ['first_name' => 'Rudy', 'last_name' => 'Santua'],
            ['first_name' => 'Solomon', 'last_name' => 'Pagayaman'],
            ['first_name' => 'Victor', 'last_name' => 'Abelidas'],
            ['first_name' => 'Vergilio', 'last_name' => 'Genon'],
        ];

        foreach ($drivers as $driver) {
            DB::table('drivers_list_table')->insert([
                'first_name' => $driver['first_name'],
                'last_name' => $driver['last_name'],
                'position' => 'Operator',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
