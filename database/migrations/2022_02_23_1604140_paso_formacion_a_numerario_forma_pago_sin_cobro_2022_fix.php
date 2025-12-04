<?php

use App\UsuarioPago;
use App\UsuarioPagoSocio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PasoFormacionANumerarioFormaPagoSinCobro2022Fix extends Migration
{
    /**
     * 3ways - Euro Fuenmayor
     * Migración requerida fix paso formacion a numerario que tienen metodo por defecto sin cargo
     * Run the migrations.
     *
     * @return void
     */
    public function up()

    {
        $usuarios_numerarios_cuota_2022_con_metodo_pago_por_defecto_sin_cargo = DB::table('usuarios_pagos')
            ->join('usuarios_pagos_socios', 'usuarios_pagos.id', '=', 'usuarios_pagos_socios.usuario_pago_id')
            ->join('usuarios_socios', 'usuarios_pagos.usuario_id', '=', 'usuarios_socios.usuario_id')
            ->where('usuarios_pagos_socios.cuota_year', 2022)
            ->where('usuarios_socios.tipo_socio', 5)
            ->whereIN('usuarios_pagos.estado_pago_id', [2,4])
            ->where('usuarios_socios.socios_pagos_metodos_id', 20)
            ->whereNull('usuarios_pagos.deleted_at')
            ->whereNull('usuarios_pagos_socios.deleted_at')
            ->whereNull('usuarios_socios.deleted_at')
            ->get();

        foreach ($usuarios_numerarios_cuota_2022_con_metodo_pago_por_defecto_sin_cargo as $row) {
            $usuario_id = $row->usuario_id;
            $usuario_socio = \App\UsuarioSocio::where('usuario_id', $usuario_id);
            $usuario_socio->update([
                'socios_pagos_metodos_id' => 3
            ]);
        }
        \Illuminate\Support\Facades\Log::debug('$usuarios_numerarios_cuota_2022_con_metodo_pago_por_defecto_sin_cargo fix 2022-23-02 count: '.print_r($usuarios_numerarios_cuota_2022_con_metodo_pago_por_defecto_sin_cargo->count(),true));
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
