<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Pizza\IngredientesUsuarioController;
use App\Http\Controllers\Pizza\TrocaIngredienteController;

use App\Http\Repositories\Pizza\IngredientesUsuarioRepository;
use App\Http\Repositories\Pizza\TrocaIngredienteRepository;
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

Route::post('pizza/criartroca/', function(Request $request){
    $data = $request->all();
    $trocaIngredientesRepository = new TrocaIngredienteRepository();
    $ingredientesUsuarioRepository = new IngredientesUsuarioRepository();
    $controller = new TrocaIngredienteController($trocaIngredientesRepository, $ingredientesUsuarioRepository);
    //passar todo request e separar no controller

    try{
        $troca = $controller->setTrocaIngrediente($data);
        return response()->json(['post' => $data, 'debug'=>$troca], 201);
    }catch(Exception $e){
        return response()->json(['post' => $data, 'error' => $e], 418);
    }
});

Route::post('pizza/confirmatroca/', function(Request $request){
    $data = $request->all(); 
    $trocaIngredientesRepository = new TrocaIngredienteRepository();
    $ingredientesUsuarioRepository = new IngredientesUsuarioRepository();
    $controller = new TrocaIngredienteController($trocaIngredientesRepository, $ingredientesUsuarioRepository);
    
    // try{
        $troca = $controller->confirmaTrocaIngrediente($data);
        return response()->json(['post' => $data, 'debug'=>$troca], 201);
    // }catch(Exception $e){
    //     return response()->json(['post' => $data, 'error' => $e], 418);
    // }
});
Route::post('pizza/cancelatroca/', function(Request $request){});
//Route::post('pizza/cancelatrocas/', function(Request $request){};
