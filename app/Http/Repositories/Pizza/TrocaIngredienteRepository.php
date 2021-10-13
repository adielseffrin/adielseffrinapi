<?php

namespace App\Http\Repositories\Pizza;
use App\Http\Interfaces\Pizza\TrocaIngredienteInterface;
use App\Models\Pizza\TrocaIngrediente;

class TrocaIngredienteRepository implements TrocaIngredienteInterface{
    
    public function setTrocaIngrediente($twitch_id, $ingrediente_id, $quantidade, $ingrediente_requerido_id, $quantidade_requerida){
        return TrocaIngrediente::insert([
            'twitch_id' => $twitch_id,
            'id_ingrediente' => $ingrediente_id,
            'quantidade' => $quantidade,
            'id_ingrediente_requerido' => $ingrediente_requerido_id,
            'quantidade_requerida' => $quantidade_requerida
            ]);
    }

    //0 IDLE, 1 COMPLETED, 2 CANCELED, 3 EXPIRED
    private function setStatusTroca($id_troca, $status){
        return TrocaIngrediente::where([ 'id' => $id_troca,])
        ->first()
        ->update(array('status' => $status)); 
    }

    public function confirmaTroca($id_troca)
    {
        return $this->setStatusTroca($id_troca, 1);
    }

    public function cancelaTroca($id_troca)
    {
        return $this->setStatusTroca($id_troca, 2);
    }

    public function expiraTroca($id_troca)
    {
        return $this->setStatusTroca($id_troca, 3);
    }

    public function buscarDadosTroca($troca_id){
        // return TrocaIngrediente::where(['id' => $troca_id])->get();
        return \DB::table('trocas_ativas')
        ->select('*')
        ->where([
            'id' => $troca_id,
        ])
        ->first();
    }

    public function buscarTrocasAtivas(){}
}