<?php
namespace Http;
use \Client;
/**
 * A sungloton class this class create a session with curl_init if dont exeist and send a https request with fetch
 */
class Request{
    public static $ch;

    /**
     * fetch a url
     * @param string $url
     * @return string|bool data
     */
    public static function Fetch(string $url){
        if (Request::$ch == null) Request::$ch = curl_init();
        curl_setopt(Request::$ch, CURLOPT_URL, $url); 
        curl_setopt(Request::$ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(Request::$ch, CURLOPT_HEADER, 0);
        $data = curl_exec(Request::$ch);
        return $data;
    }

    public static function closeUrl(){
        curl_close(Request::$ch);
        $ch = null;
    }
}


?>