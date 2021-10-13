<?php

namespace App\Http\Interfaces\Pizza;

interface IngredientesUsuarioInterface{
    public function findIngredientsByTwitchId($id_twitch);
    public function checkIngredienteByTwitchId($ingredient_id, $twitch_id);
    public function removeIngredienteByTwitchId($ingredient_id, $twitch_id, $quantidade);
    public function addIngredienteByTwitchId($ingredient_id, $twitch_id, $quantidade);
}