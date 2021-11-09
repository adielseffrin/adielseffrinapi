<?php

namespace App\Http\Repositories\Pizza;
use App\Http\Interfaces\Pizza\TentativasFomeInterface;
use App\Models\Pizza\TentativasFome;

class TentativasFomeRepository implements TentativasFomeInterface{
    public function getUserPoints($twitch_id){
        return \DB::table('tentativas_fome')
        ->select('tentativas_fome.pontos')
        ->join('usuarios','usuarios.id','=','tentativas_fome.id_usuario')
        ->where(['usuarios.twitch_id' => $twitch_id])
        ->where('tentativas_fome.data_tentativa','>=',date('Y-m-01'))
        ->where('tentativas_fome.data_tentativa','<=',date('Y-m-t'))
        ->get()
        ->sum('pontos') ;   
    }
}