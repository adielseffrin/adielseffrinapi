<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\Pizza\IngredientesUsuarioInterface;

class IngredientesUsuarioController extends Controller
{
    private $_ingredientesUsuarioInterface;
    public function __construct(IngredientesUsuarioInterface $ingredientesUsuarioInterface){
        $this->_ingredientesUsuarioInterface = $ingredientesUsuarioInterface;
    }

    public function findIngredientsByTwitchId($id)
    {
        return $this->_ingredientesUsuarioInterface->findIngredientsByTwitchId($id);
    }

    public function checkIngredienteByTwitchId($ingredient_id, $twitch_id)
    {
        return $this->_ingredientesUsuarioInterface->checkIngredienteByTwitchId($ingredient_id, $twitch_id);
    }

}
