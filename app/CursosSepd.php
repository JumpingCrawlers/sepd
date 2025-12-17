<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CursosSepd extends Model
{
    protected $table = 'cursos_sepd';

    private const HASH_METHOD = 'aes-128-ecb';
    
    private const HASH_KEY = 'vzW+V<@9}{8R-fYC';

    /**
     * Encrypt an array
     *
     * @param array $str
     *
     * @return string
     */
    function encrypt(array $array)
    {
        return openssl_encrypt(json_encode($array), self::HASH_METHOD, self::HASH_KEY);
    }

    public function getUrlManualGestivosSepdAttribute ()
    {
        $enlace = explode(":", $this->enlace);
        
        $url = 'https://elearningdigestivo.es/cursos/' . $enlace[0];

        $user = auth()->user();

        if ($user && $user->socio) {
            
            $encrypted = $this->encrypt([
                'mail' => $user->email,
                'nombre' => $user->nombre,
                'apellidos' => $user->apellidos,
                'origen' => 'sepd'
            ]);

            $urlencode = urlencode($encrypted);

            return $url  .'/?user_token=' . $urlencode;
        }

        return $url;
    }
}
