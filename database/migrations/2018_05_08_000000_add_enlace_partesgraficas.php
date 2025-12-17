<?php

use Illuminate\Database\Migrations\Migration;

class AddEnlacePartesgraficas extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('partesgraficas', function ($table) {
            if (!Schema::hasColumn('partesgraficas', 'enlace')) {
                $table->string('enlace', 255)->after('imagen')->nullable();
                $table->enum('destino_enlace', ['Por defecto', 'Popup', 'Nuevo'])->after('enlace')->default('Por defecto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('partesgraficas', 'enlace')) {
            Schema::table('partesgraficas', function (Blueprint $table) {
                $table->dropColumn('enlace');
            });
        }
        if (Schema::hasColumn('partesgraficas', 'destino_enlace')) {
            Schema::table('partesgraficas', function (Blueprint $table) {
                $table->dropColumn('destino_enlace');
            });
        }
    }

}
