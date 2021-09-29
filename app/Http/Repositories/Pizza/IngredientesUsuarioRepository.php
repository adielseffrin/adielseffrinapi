<?php

namespace App\Http\Repositories\Pizza;
use App\Http\Interfaces\Pizza\IngredientesUsuarioInterface;

class IngredientesUsuarioRepository implements IngredientesUsuarioInterface{
    public function findIngredientsByTwitchId($id){
        return \DB::table('ingredientes_usuario')
        ->select('ingredientes_usuario.id_ingrediente','ingredientes_usuario.quantidade', 'ingredientes.descricao')
        ->join('usuarios','usuarios.id','=','ingredientes_usuario.id_usuario')
        ->join('ingredientes','ingredientes.id','=','ingredientes_usuario.id_ingrediente')
        ->where(['usuarios.twitch_id' => $id])
        ->get();
    }
}