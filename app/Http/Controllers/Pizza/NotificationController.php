<?php

namespace App\Http\Controllers\Pizza;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\Pizza\NotificationInterface;

class NotificationController extends Controller
{
    private $_notificationInterface;
    public function __construct(NotificationInterface $notificationInterface){
        $this->_notificationInterface = $notificationInterface;
    }

    public function notificateExtensionClients($message)
    {
        $this->_notificationInterface->run();
        $this->_notificationInterface->notificateExtensionClients($message);
        $this->_notificationInterface->disconnect();
    }

    

}
