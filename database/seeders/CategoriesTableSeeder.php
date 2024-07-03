<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'ACCESSIBILITY'],
            ['name' => 'BEST_PRACTICES'],
            ['name' => 'PERFORMANCE'],
            ['name' => 'PWA'],
            ['name' => 'SEO'],
        ];

        // Insertar los datos en la tabla 'categories'
        DB::table('categories')->insert($categories);
    }
}
