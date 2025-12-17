<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UsuarioItem;

class ItemController extends Controller
{
    /* 3 Ways - Alexis Bogado */

    /**
     * Registrar tiempo del usuario viendo un item
     *
     * @return mixed
     */
    public function usuarioTiempo($id, Request $request) {
        $user = Auth::user();

        if (!$request->input("item_id") || !$request->input("time")) return;
        $usuario_curso = $user->usuario_cursos->where('curso_id', $id)->first();
        DiplomasController::comprobar_diploma($usuario_curso);
        $this->storeTiempo($request);
        /**
         * 3 ways Euro Fuenmayor
         * Retorna JSON con el tiempo cumplido ?
         **/
        $is_item_completed = false;
        $usuario_item = UsuarioItem::where('usuario_id', $user->id)->where('item_id', $request->input("item_id"))->first();
        if($usuario_item){
            $is_item_completed = User::is_item_time_completed($usuario_item);
        }
        $is_partial_time_item = !is_null($usuario_item) && !$is_item_completed;
        return response()->json(compact('is_item_completed', 'is_partial_time_item'));
    }

    /**
     * Registrar o actualizar tiempo en la base de datos
     * 
     * @return void
     */
    public function storeTiempo(Request $request) {
        $user = Auth::user();

        $item = UsuarioItem::where('usuario_id', '=', $user->id)->where('item_id', '=', $request->input('item_id'))->first();
        if (!is_null($item)):
            $item->tiempo += intval($request->input('time'));
        else:
            $item = new UsuarioItem;
            $item->item_id = intval($request->input('item_id'));
            $item->usuario_id = $user->id;
            $item->tiempo = intval($request->input('time'));
        endif;

        $item->save();
    }
}
