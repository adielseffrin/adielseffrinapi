<?php

namespace App\Http\Repositories\Pizza;
use App\Http\Interfaces\Pizza\UsuarioInterface;
use App\Models\Pizza\Usuarios;

class UsuarioRepository implements UsuarioInterface{
    public function getUserInfo($twitch_id){
        return Usuarios::where(['twitch_id' => $twitch_id])->first();
    }
}