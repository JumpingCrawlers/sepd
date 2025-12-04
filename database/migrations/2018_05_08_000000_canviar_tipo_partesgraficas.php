<?php

use Illuminate\Database\Migrations\Migration;

class CanviarTipoPartesgraficas extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('partesgraficas', function ($table) {
            $table->enum('tipo', ['boton', 'imagen', 'rrss'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('partesgraficas', function ($table) {
            $table->enum('tipo', ['boton', 'imagen'])->change();
        });
    }

}
