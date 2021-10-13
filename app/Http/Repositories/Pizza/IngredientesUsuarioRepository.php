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

    public function checkIngredienteByTwitchId($ingredient_id, $twitch_id){
        return \DB::table('ingredientes_usuario')
        ->select('ingredientes_usuario.quantidade')
        ->join('usuarios','usuarios.id','=','ingredientes_usuario.id_usuario')
        ->where([
            'usuarios.twitch_id' => $twitch_id,
            'ingredientes_usuario.id_ingrediente' => $ingredient_id
            ])
        ->first();
    }

    public function removeIngredienteByTwitchId($ingredient_id, $twitch_id, $quantidade){
        return \DB::table('ingredientes_usuario')
        ->join('usuarios','usuarios.id','=','ingredientes_usuario.id_usuario')
        ->where([
            'usuarios.twitch_id' => $twitch_id,
            'ingredientes_usuario.id_ingrediente' => $ingredient_id
            ])
        ->limit(1)
        ->update(array('ingredientes_usuario.quantidade' => $quantidade)); 
    }

    //revisar no caso de não existir o registro
    public function addIngredienteByTwitchId($ingredient_id, $twitch_id, $quantidade){
        return \DB::table('ingredientes_usuario')
        ->join('usuarios','usuarios.id','=','ingredientes_usuario.id_usuario')
        ->where([
            'usuarios.twitch_id' => $twitch_id,
            'ingredientes_usuario.id_ingrediente' => $ingredient_id
            ])
        ->limit(1)
        ->increment('ingredientes_usuario.quantidade', $quantidade);
    }
}