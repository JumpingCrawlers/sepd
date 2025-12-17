<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 3 ways - Euro Fuenmayor
 */

class addPlataformaPordefectoSugerenciasPordefectoTextoCabeceraToEncuestasTable extends Migration
{

    /**
     * 3 ways - Euro Fuenmayor
     *  Migración requerida para hacer personalizable texto de cabecera y la posibilidad de agregar preguntas por defecto para las categorias plataforma y sugerencias
     */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('encuestas', function (Blueprint $table) {
//            $table->string('plataforma_pordefecto')->nullable(true)->default(null)->after('titulo');
//            $table->string('sugerencias_pordefecto')->nullable(true)->default(null)->after('titulo');
            $table->string('texto_cabecera')->after('titulo')->default('VALORE DEL 1 AL 5 (1: insatisfacción total, 5: satisfacción total)');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('encuestas', function (Blueprint $table) {
//            $table->dropColumn('plataforma_pordefecto');
//            $table->dropColumn('sugerencias_pordefecto');
            $table->dropColumn('texto_cabecera');
        });
    }
}
