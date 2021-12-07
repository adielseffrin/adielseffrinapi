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
        $this->endpoint = 'chat';
    }

    public function notificateExtensionClients($message){
        error_log("Vou tentar mandar a mensagem: ".json_encode($message),0);
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
        // if($this->connection){
        //     $this->disconnect();
        // }
        $this->run();
    }

    public function run(){
        \Amp\Loop::run(function () {
            try{
                $url = "ws://{$this->host}:{$this->port}/{$this->endpoint}";
                error_log("URL: ".$url,0);
                $connection = yield Client\connect($url);
                $this->connection = $connection;
                yield $connection->send('Hello!');
            }catch(Exception $e){
                var_dump($e);
                error_log("Error on connect on WS: ".json_encode($e),0);
            }
            \Amp\Loop::stop();
            
        });
    }

    
}