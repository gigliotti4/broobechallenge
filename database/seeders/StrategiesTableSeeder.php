<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrategiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $strategies = [
            ['name' => 'DESKTOP'],
            ['name' => 'MOBILE'],
        ];

        // Insertar los datos en la tabla 'strategies'
        DB::table('strategies')->insert($strategies);
    }
}
