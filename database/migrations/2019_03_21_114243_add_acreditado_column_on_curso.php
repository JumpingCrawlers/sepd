<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcreditadoColumnOnCurso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('elearning__curso', function (Blueprint $table) {

            $table->tinyInteger('acreditado')->after('num_preguntas')->nullable();
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('elearning__curso', function (Blueprint $table) {

            $table->dropColumn('acreditado');
    
        });
    }
}
