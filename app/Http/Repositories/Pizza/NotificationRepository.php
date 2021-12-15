<?php

namespace App\Http\Repositories\Pizza;
use App\Http\Interfaces\Pizza\NotificationInterface;

use Amp\Delayed;
use Amp\Websocket;
use Amp\Websocket\Client;

class NotificationRepository implements NotificationInterface{
    private $host;
    private $port;
    private $endpoint;
    private $connection;

    public function __construct() {
        $this->host = 'ws.adielseffr.in';
        $this->port = '8080';
        $this->endpoint = 'pizza';
    }

    public function notificateExtensionClients($message){
        if($this->connection){
            $this->connection->send($message);
        }else{
            error_log("Not connected on WS",0);
        }
    }

    public function disconnect(){
        $this->connection->close();
    }

    public function connect(){
        $this->run();
    }

    public function run(){
        \Amp\Loop::run(function () {
            try{
                $url = "ws://{$this->host}:{$this->port}/{$this->endpoint}";
                $connection = yield Client\connect($url);
                $this->connection = $connection;
                //yield $connection->send('Hello!');
            }catch(Exception $e){
                var_dump($e);
                error_log("Error on connect on WS: ".json_encode($e),0);
            }
            \Amp\Loop::stop();
            
        });
    }

    
}