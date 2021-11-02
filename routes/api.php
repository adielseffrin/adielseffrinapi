<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

use App\Http\Controllers\Pizza\IngredientesUsuarioController;
use App\Http\Controllers\Pizza\UsuarioController;
use App\Http\Controllers\Pizza\TrocaIngredienteController;

use App\Http\Repositories\Pizza\IngredientesUsuarioRepository;
use App\Http\Repositories\Pizza\UsuarioRepository;
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

Route::get('pizza/info', function(Request $request){

    $twitch_token = $request->header('JWT');
    $token = new Token($twitch_token);
    $payload = JWTAuth::setToken($token->get())->getPayload();

    $id = $payload['user_id'];
    $ingredientesUsuarioRepository = new IngredientesUsuarioRepository();
    $controllerIngr = new IngredientesUsuarioController($ingredientesUsuarioRepository);
    $ingredientes =  $controllerIngr->findIngredientsByTwitchId($id);
    
    $UsuarioRepository = new UsuarioRepository();
    $controllerUser = new UsuarioController($UsuarioRepository);
    $userInfo =  $controllerUser->getUserInfo($id);

    return json_encode( array('info'=> $userInfo, 'ingredientes'=> $ingredientes));
    
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
