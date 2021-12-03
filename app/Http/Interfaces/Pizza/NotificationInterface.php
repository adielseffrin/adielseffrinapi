<?php

namespace App\Http\Interfaces\Pizza;

interface NotificationInterface{
    public function notificateExtensionClients($message);
    public function disconnect();
    public function connect();
    public function run();
    
}