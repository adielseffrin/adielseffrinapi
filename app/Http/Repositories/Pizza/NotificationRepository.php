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

    function __construct() {
        $this->host = 'ws.adielseffr.in';
        $this->port = '8080';
        $this->endpoint = 'chat';
    }

    public function notificateExtensionClients($message){
        if($this->connection){
            $this->connection->send($message);
        }
    }

    public function disconnect(){
        $this->connection->close();
    }

    public function connect(){
        if($this->connection){
            $this->disconnect();
        }
        $this->run();
    }

    public function run(){
        \Amp\Loop::run(function () {
            try{
                $url = "ws://{$this->host}:{$this->port}/{$this->endpoint}";
                $connection = yield Client\connect($url);
                $this->connection = $connection;
                yield $connection->send('Hello!');
            }catch(Exception $e){
                var_dump($e);
            }
            \Amp\Loop::stop();
            
        });
    }

    
}