<?php

namespace App\Http\Interfaces\Pizza;

interface IngredientesUsuarioInterface{
    public function findIngredientsByTwitchId($id_twitch);
}