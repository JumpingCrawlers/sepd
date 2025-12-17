<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUltimaActSitio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* crear disparadores al insertar, actualizar o eliminar en paginas, partesgraficas, sliders, calendario, empleo
        para actualizar la fecha de ultima modificacion del sitio*/
        DB::unprepared('

        CREATE TRIGGER `paginas_ultima_act_sitio` BEFORE INSERT 
        ON `paginas` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `paginas_upd_ultima_act_sitio` BEFORE UPDATE 
        ON `paginas` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `paginas_del_ultima_act_sitio` BEFORE DELETE 
        ON `paginas` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();



        CREATE TRIGGER `partesgraficas_ultima_act_sitio` BEFORE INSERT 
        ON `partesgraficas` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `partesgraficas_upd_ultima_act_sitio` BEFORE UPDATE 
        ON `partesgraficas` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `partesgraficas_del_ultima_act_sitio` BEFORE DELETE 
        ON `partesgraficas` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();



        CREATE TRIGGER `sliders_ultima_act_sitio` BEFORE INSERT 
        ON `sliders` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `sliders_upd_ultima_act_sitio` BEFORE UPDATE 
        ON `sliders` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `sliders_del_ultima_act_sitio` BEFORE DELETE 
        ON `sliders` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();



        CREATE TRIGGER `calendario_ultima_act_sitio` BEFORE INSERT 
        ON `calendario` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `calendario_upd_ultima_act_sitio` BEFORE UPDATE 
        ON `calendario` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `calendario_del_ultima_act_sitio` BEFORE DELETE 
        ON `calendario` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();


        
        CREATE TRIGGER `empleo_ultima_act_sitio` BEFORE INSERT 
        ON `empleo` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `empleo_upd_ultima_act_sitio` BEFORE UPDATE 
        ON `empleo` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        CREATE TRIGGER `empleo_del_ultima_act_sitio` BEFORE DELETE 
        ON `empleo` FOR EACH ROW 
        UPDATE sitio SET updated_at=NOW();

        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER `paginas_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `paginas_upd_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `paginas_del_ultima_act_sitio`');

        DB::unprepared('DROP TRIGGER `partesgraficas_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `partesgraficas_upd_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `partesgraficas_del_ultima_act_sitio`');

        DB::unprepared('DROP TRIGGER `sliders_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `sliders_upd_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `sliders_del_ultima_act_sitio`');

        DB::unprepared('DROP TRIGGER `calendario_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `calendario_upd_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `calendario_del_ultima_act_sitio`');

        DB::unprepared('DROP TRIGGER `empleo_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `empleo_upd_ultima_act_sitio`');
        DB::unprepared('DROP TRIGGER `empleo_del_ultima_act_sitio`');
    }
}