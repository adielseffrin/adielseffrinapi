<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pizza\IngredientesUsuario;

class IngredientesUsuarioController extends Controller
{
    public function findIngredientsByTwitchId($id)
    {
        return \DB::table('ingredientes_usuario')
        ->select('ingredientes_usuario.id_ingrediente','ingredientes_usuario.quantidade', 'ingredientes.descricao')
        ->join('usuarios','usuarios.id','=','ingredientes_usuario.id_usuario')
        ->join('ingredientes','ingredientes.id','=','ingredientes_usuario.id_ingrediente')
        ->where(['usuarios.twitch_id' => $id])
        ->get();
    }

}
