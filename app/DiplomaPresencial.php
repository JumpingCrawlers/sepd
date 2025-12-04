<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Diploma;

/**
 * Clase DiplomaPresencial
 *
 * Representa un modelo Eloquent para la tabla `diplomas_presenciales`.
 * Este modelo permite interactuar con la tabla `diplomas_presenciales` en la base de datos.
 */
class DiplomaPresencial extends Model
{
    /**
     * @var string $table Nombre de la tabla asociada al modelo.
     */
    protected $table = "diplomas_presenciales";

    /**
     * @var array $fillable Los atributos que son asignables en masa.
     *
     * Esta propiedad define qué atributos pueden ser asignados en masa al crear o
     * actualizar un modelo.
     */
    protected $fillable = [
        'id_evento',
        'diploma_id',
        'nombre',
        'apellidos',
        'dni',
        'email',
        'creditos',
        'evento',
        'sesion',
        'tiempo',
        'num_expediente'
    ];

    /**
     * Relación con el modelo AcreditacionesEvento.
     *
     * Esta función define una relación uno a uno con el modelo AcreditacionesEvento
     * utilizando la columna `id_evento` como clave foránea.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function acreditacion_evento()
    {
        return $this->hasOne(AcreditacionesEvento::class, 'id_evento', 'id_evento');
    }

    /**
     * Relación con el modelo Diploma.
     *
     * Esta función define una relación uno a uno con el modelo Diploma
     * utilizando la columna `diploma_id` como clave foránea.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function diploma()
    {
        return $this->belongsTo(Diploma::class, 'diploma_id');
    }
}
