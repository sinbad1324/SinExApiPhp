<?php 
namespace Services\Auth;
use Google\Client;
use Google\Service\Gmail;

class GoogleAuthService {
    public static $client ;
   
    public static function Init(){
        if (!GoogleAuthService::$client) {
            GoogleAuthService::$client= new Client();
            GoogleAuthService::$client->setApplicationName($_ENV['GOOGLE_ID']);
            GoogleAuthService::$client->setDeveloperKey($_ENV['GOOGLE_SECRET']);
        }
    }
    
    public static function GetURLForClient() :string {
        GoogleAuthService::Init();
        $client = GoogleAuthService::$client;
        $client->addScope(Gmail::MAIL_GOOGLE_COM);
        $client->setRedirectUri("http://localhost:8080/api/auth/google-get-connection");
        $client->setAccessType('online');
        $client->setIncludeGrantedScopes(true);
        return $client->createAuthUrl();
    }
}


?>