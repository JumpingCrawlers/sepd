<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SitioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sitio')->insert([
            'nombre' => 'SEPD',
            'url' => 'https://www.sepd.es/',
            'created_at' => '2018-08-01 00:00:00',
        ]);
    }
}
