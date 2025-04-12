<?php
namespace Http;
use \Client;
class Request{
    public static $client;
    
    public static function Get(string $url){
        if (Request::$client == null) Request::$client = new Client();

    }
}


?>