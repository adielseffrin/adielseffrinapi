<?php

namespace App\Http\Interfaces\Pizza;

interface TrocaIngredienteInterface{
    public function setTrocaIngrediente($twitch_id, $ingrediente_id, $quantidade, $ingrediente_requerido_id, $quantidade_requerida);
    public function buscarDadosTroca($troca_id);
    public function confirmaTroca($id_troca);
    public function cancelaTroca($id_troca);
    public function expiraTroca($id_troca);
    public function buscarTrocasAtivas();
}