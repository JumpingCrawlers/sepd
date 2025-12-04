<?php

use App\UsuarioPago;
use App\UsuarioPagoSocio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LanzamientoPagoMetodoTarjeta2022Fix extends Migration
{
    /**
     * 3ways - Euro Fuenmayor
     * Migración requerida fix lanzamiento de pago 2022 - Forma de pago: tarjeta
     * Run the migrations.
     *
     * @return void
     */
    public function up()

    {

        $usuarios_que_pagan_via_tarjeta_con_estado_pago_2022_ya_creado = DB::table('usuarios_pagos')
            ->join('usuarios_pagos_socios', 'usuarios_pagos.id', '=', 'usuarios_pagos_socios.usuario_pago_id')
            ->join('usuarios_socios', 'usuarios_pagos.usuario_id', '=', 'usuarios_socios.usuario_id')
            ->where('usuarios_pagos_socios.cuota_year', 2022)
            ->whereIN('usuarios_pagos.estado_pago_id', [2,4])
            ->where('usuarios_socios.socios_pagos_metodos_id', 24)
            ->whereNull('usuarios_pagos.deleted_at')
            ->whereNull('usuarios_pagos_socios.deleted_at')
            ->whereNull('usuarios_socios.deleted_at')
            ->get();

        $usuarios_pendientes_de_pagar_via_tarjeta = DB::table('usuarios_socios')
            ->join('socios_tipos', 'socios_tipos.id', '=', 'usuarios_socios.tipo_socio')
            ->where('usuarios_socios.socios_pagos_metodos_id', 24)
            ->where('socios_tipos.cuota', '>', 0)
            ->whereNull('usuarios_socios.deleted_at')
            ->whereNotIn('usuarios_socios.usuario_id', array_column($usuarios_que_pagan_via_tarjeta_con_estado_pago_2022_ya_creado->toArray(), 'usuario_id'))
            ->get();

        foreach ($usuarios_pendientes_de_pagar_via_tarjeta as $row) {
            $usuario_id = $row->usuario_id;
            $usuario_pago = new UsuarioPago;
            $usuario_pago->usuario_id = $usuario_id;
            $usuario_pago->estado_pago_id = 2;
            $usuario_pago->metodo_pago_id = $row->socios_pagos_metodos_id;
            $usuario_pago->precio = $row->cuota;
            $usuario_pago->updated_at = null;
            $usuario_pago->save();
            $nuevo_pago_socio = new UsuarioPagoSocio;
            $nuevo_pago_socio->usuario_pago_id = $usuario_pago->id;
            $nuevo_pago_socio->sociedad_id = 122;
            $nuevo_pago_socio->socio_tipo_id = $row->tipo_socio;
            $nuevo_pago_socio->cuota_year = 2022;
            $nuevo_pago_socio->ejercicio_id = 14;
            $nuevo_pago_socio->updated_at = null;
            $nuevo_pago_socio->save();
        }
        \Illuminate\Support\Facades\Log::debug('$usuarios_pendientes_de_pagar_via_tarjeta fix 2022-10-02 count: '.print_r($usuarios_pendientes_de_pagar_via_tarjeta->count(),true));
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
