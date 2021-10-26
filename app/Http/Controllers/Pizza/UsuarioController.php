<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\Pizza\UsuarioInterface;

class UsuarioController extends Controller
{
    private $_usuarioInterface;
    public function __construct(UsuarioInterface $usuarioInterface){
        $this->_usuarioInterface = $usuarioInterface;
    }

    public function getUserInfo($id)
    {
        return $this->_usuarioInterface->getUserInfo($id);
    }

    
}
