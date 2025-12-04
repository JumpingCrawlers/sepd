<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BiblioFormatoTableFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $process_1 = DB::table('elearning__biblio_formato')->insertGetId([
            'formato' => 'Comunicación Oral',
            'ordenacion' => 'presentacion',
        ]);
        $process_2 = DB::table('elearning__biblio_formato')->insertGetId([
            'formato' => 'Comunicación Póster Oral',
            'ordenacion' => 'presentacion',
        ]);
        $process_3 = DB::table('elearning__biblio_formato')->insertGetId([
            'formato' => 'Comunicación Póster',
            'ordenacion' => 'presentacion',
        ]);

        if($process_1&&$process_2&&$process_3){
            DB::delete(DB::raw("DELETE FROM areas WHERE id in (7,8,9)"));
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
