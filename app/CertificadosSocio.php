<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificadosSocio extends Model
{
	use SoftDeletes;

    protected $dates = ['deleted_at'];
	
	protected $table = 'certificados_socios';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'cargo_institucional_id',
        'reconocimiento_id',
        'template',
        'content',
    ];

    public function cargo ()
    {
        return $this->belongsTo(\App\CargosInstitucionales::class, 'cargo_institucional_id');
    }

    public function reconocimiento ()
    {
        return $this->belongsTo(\App\SocioTipoReconocimiento::class, 'reconocimiento_id');
    }

  /**
     * Obtener el texto del tipo de reconocimiento para el PDF
     *
     * @return string
     */
    public function getTextTypeReconocimientoAttribute () : string
    {
        switch ($this->reconocimiento_id) {
            // SI HONORIFICO -> 
            case '1':
                return "por haber prestado sus servicios y apoyo extraordinario a la SEPD,";
                break;
                
            // SI MEDALLA ORO
            case 2:
            case '2':
                return "por haber pertenecido a la SEPD ininterrumpidamente veinticinco años, y por haber sido distinguido con el reconocimiento honorífico SEPD y como Colaborador Platino,";
                break;
            case 1:

            // SI PLATINO ->
            case 3:
            case '3':
                return "en agradecimiento a sus 6 años como responsable de un comité institucional SEPD/FEAD,";
                break;
                
            // SI ORO ->
            case 4:
            case '4':
                return "en agradecimiento a sus 10 años de continua colaboración institucional con la SEPD,";
                break;
            // SI PLATA ->
            case 5:
            case '5':
                return "en agradecimiento a sus 5 años de continua colaboración institucional con la SEPD,";
                break;
            default:
                return '';
                break;
        }
    }
}
