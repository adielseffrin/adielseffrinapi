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
        $quantidade = $quantidade[0]->quantidade;
        
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
    //TODO: REVISAR - não remove mais o elemento, soma o novo, não apaga a troca
    public function confirmaTrocaIngrediente($data){
        //buscar dados da troca
        $dadosTroca = $this->_trocaIngredienteInterface->buscarDadosTroca($data['troca_id']);
        
        //verificar se a troca pode ocorrer (quantidade no usuario)
        //verificar disponibilidade do ingrediente no aceitador
        $quantidade = $this->_ingredientesUsuarioInterface->checkIngredienteByTwitchId($dadosTroca->id_ingrediente_requerido, $data['twitch_id']);
        $quantidade = $quantidade->quantidade;
        if($quantidade >= $dadosTroca->quantidade_requerida){
            //remover e salvar ingr solicitado na troca do destinatario (aceita a troca)
            $novaQuantidade = $quantidade - $dadosTroca->quantidade_requerida;
            $this->_ingredientesUsuarioInterface->removeIngredienteByTwitchId($dadosTroca->id_ingrediente_requerido, $data['twitch_id'], $novaQuantidade);

            //adiciona no aceitador ingredientes da troca
            
            $this->_ingredientesUsuarioInterface->addIngredienteByTwitchId($dadosTroca->id_ingrediente, $data['twitch_id'], $dadosTroca->quantidade);
            //adiciona no solicitador, ingredientes do aceitador
            $this->_ingredientesUsuarioInterface->addIngredienteByTwitchId($dadosTroca->id_ingrediente_requerido, $dadosTroca->twitch_id, $quantidade);
            //apaga registro da troca
            $this->_trocaIngredienteInterface->confirmaTroca($data['troca_id']);
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
