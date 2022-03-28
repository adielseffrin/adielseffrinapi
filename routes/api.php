<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

use App\Http\Controllers\Pizza\IngredientesUsuarioController;
use App\Http\Controllers\Pizza\UsuarioController;
use App\Http\Controllers\Pizza\TrocaIngredienteController;
use App\Http\Controllers\Pizza\TentativasFomeController;
use App\Http\Controllers\Pizza\NotificationController;

use App\Http\Repositories\Pizza\IngredientesUsuarioRepository;
use App\Http\Repositories\Pizza\UsuarioRepository;
use App\Http\Repositories\Pizza\TrocaIngredienteRepository;
use App\Http\Repositories\Pizza\TentativasFomeRepository;
use App\Http\Repositories\Pizza\NotificationRepository;
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

Route::get('pizza/ping', function(){
    $headers = [
        'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
        'Access-Control-Allow-Headers'=> 'X-Requested-With, Content-Type, X-Auth-Token, Origin, Authorization, Jwt',
        'Access-Control-Allow-Origin'=> '*',
        'Access-Control-Allow-Credentials'=> 'true'
    ];
    return \Response::make('PONG', 200, $headers);
});

Route::post('pizza/notificate', function(Request $request){
    $headers = [];
    
    $twitch_token = $request->header('JWT');
    $token = new Token($twitch_token);
    $payload = JWTAuth::setToken($token->get())->getPayload();
    
    $data = $request->all();
    $data['twitch_id'] = $payload['user_id'];

    $notificationRepository = new NotificationRepository();
    $notificationController = new NotificationController($notificationRepository);
    $retorno = $notificationController->notificateExtensionClients($data);

    return \Response::make(json_encode( array('response'=> json_encode($data))), 200, $headers);
});

Route::get('pizza/info', function(Request $request){
    // $headers = [];
    $headers = [
        'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
        'Access-Control-Allow-Headers'=> 'X-Requested-With, Content-Type, X-Auth-Token, Origin, Authorization, Jwt',
        'Access-Control-Allow-Origin'=> '*',
        'Access-Control-Allow-Credentials'=> 'true'
    ];
    
     
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
        
         $tentativaFomeRepository = new TentativasFomeRepository();
         $controllerTentativasFome = new TentativasFomeController($tentativaFomeRepository);
         $userInfo['pontos'] = $controllerTentativasFome->getUserPoints($id);
         
     return \Response::make(json_encode( array('info'=> $userInfo, 'ingredientes'=> $ingredientes, 'debug'=>$id)), 200, $headers);
});

Route::get('pizza/ingredientes', function(Request $request){
    $twitch_token = $request->header('JWT');
    $token = new Token($twitch_token);
    $payload = JWTAuth::setToken($token->get())->getPayload();

    $id = $payload['user_id'];

    $ingredientesUsuarioRepository = new IngredientesUsuarioRepository();
    $controller = new IngredientesUsuarioController($ingredientesUsuarioRepository);
    return $controller->findIngredientsByTwitchId($id);
});

Route::post('pizza/criartroca', function(Request $request){
    $twitch_token = $request->header('JWT');
    $token = new Token($twitch_token);
    $payload = JWTAuth::setToken($token->get())->getPayload();
    $data = $request->all();
    $data['twitch_id'] = $payload['user_id'];

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

//TODO verificar
Route::post('pizza/confirmatroca', function(Request $request){
    $twitch_token = $request->header('JWT');
    $token = new Token($twitch_token);
    $payload = JWTAuth::setToken($token->get())->getPayload();

    $data = $request->all(); 
    $data['twitch_id'] = $payload['user_id'];
    $trocaIngredientesRepository = new TrocaIngredienteRepository();
    $ingredientesUsuarioRepository = new IngredientesUsuarioRepository();
    $controller = new TrocaIngredienteController($trocaIngredientesRepository, $ingredientesUsuarioRepository);
    
    try{
        $troca = $controller->confirmaTrocaIngrediente($data);
        return response()->json(['post' => $data, 'debug'=>$troca], 201);
    }catch(Exception $e){
        return response()->json(['post' => $data, 'error' => $e], 418);
    }
});
Route::post('pizza/cancelatroca', function(Request $request){});
//Route::post('pizza/cancelatrocas/', function(Request $request){};
