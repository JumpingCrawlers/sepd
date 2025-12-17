<?php

use Illuminate\Database\Migrations\Migration;

class CanviarFormatoTablaPastillas extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pastillas', function ($table) {
            $table->enum('formato', ['-2', '-2-doble', '-3', '-3-doble'])->comment('2/3 columnas y doble altura')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pastillas', function ($table) {
            $table->enum('formato', ['4:3', '2:3', '0:0'])->comment("Proporción de las imágenes. 0:0 sin proporción")->change();
        });
    }

}
