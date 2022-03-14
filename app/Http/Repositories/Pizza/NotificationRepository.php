<?php

namespace App\Http\Repositories\Pizza;
use App\Http\Interfaces\Pizza\NotificationInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class NotificationRepository implements NotificationInterface{
    private $apiUrl;
    private $data;
    private $headers;
    

    public function __construct() {
        $this->apiUrl = 'https://api.twitch.tv/extensions/message/89302205';
        $this->headers = array(
            "Client-Id" => $_SERVER['CLIENT_ID']
        );
    }

    public function notificateExtensionClients($data){
        // $data = "{"ingredientes":[{"ingrediente_id":"10","quantidade":1}],"twitch_id":"517975756"}
        $message = array();
        if(isset($data->twitch_id))
            $message["twitch_id"] = $data->twitch_id;
        
        if(isset($data->ingredientes))
            $message["ingredientes"] = $data->ingredientes;

        if(isset($data->info))
            $message["info"] = $data->info;
        
        $postInput = array(
            "content_type" => "application/json",
            "targets" => array("broadcast"),
            "message" => json_encode($message)
        );

        $this->headers["Authorization"] = "Bearer ".$this->makeToken();
        
        //  return $this->headers["Authorization"];
        // return $this->makeToken();
        

        $response = Http::withHeaders($this->headers)->post($this->apiUrl, $postInput);
        $statusCode = $response->status();
        // $responseBody = json_decode($response->getBody(), true);
        return $response->getBody();
    }

    private function makeToken()
    {
        $tokenHeader = json_encode(array(
            "alg" => "HS256",
            "typ"=> "JWT"
        ));
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($tokenHeader));
        
        $date = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $date->add(new \DateInterval('PT3M'));

        $tokenPayload = json_encode(array(
            "exp"=> $date->getTimestamp(),
            "user_id"=> "89302205",
            "channel_id"=> "89302205",
            "role"=> "external",
            "pubsub_perms"=> array(
                "send"=> array(
                    "broadcast"
                    )
                    )
                ));
        $base64UrlPayload  = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($tokenPayload));
                
        $tokenSignature = hash_hmac("sha256", $base64UrlHeader.".".$base64UrlPayload,base64_decode($_SERVER['JWT_SECRET']),true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($tokenSignature));
        
        return $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;
    }
}