<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\Pizza\TentativasFomeInterface;

class TentativasFomeController extends Controller
{
    private $_tentativasFomeInterface;
    public function __construct(TentativasFomeInterface $tentativasFomeInterface){
        $this->_tentativasFomeInterface = $tentativasFomeInterface;
    }

    public function getUserPoints($id)
    {
        return $this->_tentativasFomeInterface->getUserPoints($id);
    }

    
}
