<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcreditacionesEvento extends Model
{
    protected $table ="acreditaciones__eventos";
    protected $primaryKey = 'id_evento';
    protected $fillable = ['id_evento','nombre','lugar','fecha','texto_diploma_cabecera','texto_diploma_datos_acreditacion','texto_diploma_opcional','texto_diploma_advertencia','firma'];
    private $meses = [
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre",
    ];

    /**
     * @param null $format
     * @return false|string
     */
    public function obtener_fecha_fin_diploma($format = null) {
        $fecha = $this->fecha;

        try {

            if (strpos($fecha, 'al') !== false) {
                
                $fecha_array = explode('al', $fecha);

                $fecha_fin = $fecha_array[1];

            } else {
                $fecha_fin = $fecha;
            }

            if(is_null($format)) {
                
                $fecha_fin_array = explode(' ', trim($fecha_fin));
                
                $d = $fecha_fin_array[0];
                
                $a = $fecha_fin_array[4];
                
                $m = strval(array_flip($this->meses)[ucwords(strtolower($fecha_fin_array[2]))]);

                return date("d-m-Y", strtotime("{$a}-{$m}-{$d}"));

            } else {
                return $fecha_fin;
            }
        } catch (\Throwable $th) {
            report($th);
            
            return $fecha;
        }
    }
}