<?php
/**
 * @author Alexis Bogado
 * @package front-sepd.es
 */

namespace App\Http\Controllers;

use App\Pagina;

class PruebasAppController extends Controller
{
    private const HASH_METHOD = 'aes-128-ecb';
    private const HASH_KEY = 'vzW+V<@9}{8R-fYC';

    public function index()
    {
        $pagina = Pagina::getPaginaBySlug('aula');
        $user = auth()->user();
        $encrypted = $this->encrypt([
            'mail' => $user->email,
            'nombre' => $user->nombre,
            'apellidos' => $user->apellidos,
            'dni' => $user->dni,
            'origen' => 'sepd'
        ]);

        return view('formacion.pruebas_app', compact('pagina', 'encrypted'));
    }

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

    /**
     * Decrypt a encrypted string
     *
     * @param string $encryptedStr
     * 
     * @return string
     */
    function decrypt($encryptedStr)
    {
        return openssl_decrypt($encryptedStr, self::HASH_METHOD, self::HASH_KEY);
    }
}
