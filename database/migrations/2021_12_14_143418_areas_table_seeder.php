<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AreasTableSeeder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sin_area_id = DB::table('areas')->insertGetId([
            'nombre' => 'Sin área',
            'presentacion' => '0',
            'created_at' => '2021-12-14 00:00:00',
        ]);
        if($sin_area_id){
            DB::table('elearning__biblio_documento')
                ->where('id_area', 0)
                ->update(['id_area' => $sin_area_id]);
            DB::table('elearning__biblio_area_relacion')
                ->where('id_area', 0)
                ->update(['id_area' => $sin_area_id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
