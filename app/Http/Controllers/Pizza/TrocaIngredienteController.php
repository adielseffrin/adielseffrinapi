<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\Pizza\IngredientesUsuarioInterface;
use App\Http\Interfaces\Pizza\TrocaIngredienteInterface;
use Exception;

class TrocaIngredienteController extends Controller
{
    private $_trocaIngredientesInterface;
    private $_ingredientesUsuarioInterface;

    public function __construct(
        TrocaIngredienteInterface $trocaIngredienteInterface,
        IngredientesUsuarioInterface $ingredientesUsuarioInterface
        ){
        $this->_trocaIngredienteInterface = $trocaIngredienteInterface;
        $this->_ingredientesUsuarioInterface = $ingredientesUsuarioInterface;
    }

    public function setTrocaIngrediente($data)
    {
        //verificar disponibilidade do ingrediente
        $quantidade = $this->_ingredientesUsuarioInterface->checkIngredienteByTwitchId($data['ingrediente_id'], $data['twitch_id']);
        $quantidade = $quantidade->quantidade;
        
        if($data['quantidade'] <= $quantidade){
            //retirar do inventario
            $novaQuantidade = $quantidade - $data['quantidade'];
            $this->_ingredientesUsuarioInterface->removeIngredienteByTwitchId($data['ingrediente_id'], $data['twitch_id'], $novaQuantidade);
            //adicionar na lista de troca
            $troca = $this->_trocaIngredienteInterface->setTrocaIngrediente(
                $data['twitch_id'],
                $data['ingrediente_id'],
                $data['quantidade'],
                $data['ingrediente_requerido_id'],
                $data['quantidade_requerida']);
            return $troca;
        }else{
            //funciona, porém não
            throw new Exception("Quantidade Insuficiente", 1);
        }
    }

       /*
    Confirma uma troca especifica
    Inputs:
    $id_troca - id da troca
    $twitch_id - id da pessoa que vai receber a troca os ingredientes
    */
    public function confirmaTrocaIngrediente($data){
        $dadosTroca = $this->_trocaIngredienteInterface->buscarDadosTroca($data['troca_id']);
        if($this->_trocaIngredienteInterface->validaTrocaAtiva($data['troca_id'])){
            $quantidade = $this->_ingredientesUsuarioInterface->checkIngredienteByTwitchId($dadosTroca->id_ingrediente_requerido, $data['twitch_id']);
            $quantidade = $quantidade->quantidade;
        
            if($quantidade >= $dadosTroca->quantidade_requerida){
                $novaQuantidade = $quantidade - $dadosTroca->quantidade_requerida;
                $this->_ingredientesUsuarioInterface->removeIngredienteByTwitchId($dadosTroca->id_ingrediente_requerido, $data['twitch_id'], $novaQuantidade);
                $this->_ingredientesUsuarioInterface->addIngredienteByTwitchId($dadosTroca->id_ingrediente, $data['twitch_id'], $dadosTroca->quantidade);
                $this->_ingredientesUsuarioInterface->addIngredienteByTwitchId($dadosTroca->id_ingrediente_requerido, $dadosTroca->twitch_id, $dadosTroca->quantidade_requerida);
                $this->_trocaIngredienteInterface->confirmaTroca($data['troca_id']);
            }
        }
    }

    /*
    Cancela uma troca especifica
    Inputs:
    $id_troca - id da troca
    $twitch_id - id da pessoa que vai iniciou a troca os ingredientes
    */
    public function cancelaTrocaIngrediente($data){
        //adiciona ingre da troca no solicitante
        //apaga registro da troca
    }

    /*
    Cancela todas as troca do dia anterior
    Inputs:
    $id_troca - id da troca
    $twitch_id - id da pessoa que vai iniciou a troca os ingredientes
    */
    public function cancelaTodasTrocaIngrediente(){
        //adiciona ingre da troca no solicitante
        //apaga registro da troca
    }

}
