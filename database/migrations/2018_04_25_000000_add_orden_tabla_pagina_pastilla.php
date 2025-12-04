<?php

use Illuminate\Database\Migrations\Migration;

class AddOrdenTablaPaginaPastilla extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pagina_pastilla', function ($table) {
            if (!Schema::hasColumn('pagina_pastilla', 'orden')) {
                $table->tinyInteger('orden')->default('1')->after('pastilla_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('pagina_pastilla', 'orden')) {
            Schema::table('pagina_pastilla', function ($table) {
                $table->dropColumn('orden');
            });
        }
    }
}
