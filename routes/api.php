<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Pizza\IngredientesUsuarioController;
use App\Http\Repositories\Pizza\IngredientesUsuarioRepository;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('pizza/ingredientes/{id}', function($id){
    $ingredientesUsuarioRepository = new IngredientesUsuarioRepository();
    $controller = new IngredientesUsuarioController($ingredientesUsuarioRepository);
    return $controller->findIngredientsByTwitchId($id);
});
