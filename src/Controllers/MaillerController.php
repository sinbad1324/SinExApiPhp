<?php

namespace src\Controllers;
use Mailer\Mailer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
require_once "../src/Services/mail/Mailer.php";
final class MailerController extends BaseController
{
    // ManuelAuth
  
public  function GetMailerToMe (Request $request, Response $response, $args) {
    $ipAddress = $request->getAttribute('ip_address');
    $jsonString = file_get_contents("../JSON/mailer.json");
    if (!$jsonString) {
       $response->getBody()->write(static::json("Il a y a eu une erreur !"));
        return $response; 
    }
    $jsonString = json_decode($jsonString ,true);
    if (isset($jsonString["$ipAddress"])) {
        if ((time() - $jsonString["$ipAddress"]["time"]) > 60) {
            $jsonString["$ipAddress"] = [
                "time"=>time()
            ];
        }else{
            $response->getBody()->write(static::json("Anti SPAMMMMMMMMMMM BRRAAAAAAAAAAAAAAAAAAA!"));
            return $response; 
        }
    }
    if (!isset($jsonString["$ipAddress"])) {
        $jsonString["$ipAddress"] = [
            "time"=>time()
        ];
    }
    $data = $request->getAttribute('dataBody');
    if (!$data["email"]) {
        $response->getBody()->write(static::json("Il vous manque le email!"));
        return $response;
    }
    if (!$data["name"]) {
        $response->getBody()->write(static::json("Il vous manque le name!"));
        return $response;
    }  
    if (!$data["message"]) {
        $response->getBody()->write(static::json("Il vous manque message email!"));
        return $response;
    }
    if (!Mailer::SendMe($data["email"] , $data["name"] , $data["message"])) {
        $response->getBody()->write(static::json("Nous avons pas pu envoyer un mail"));
        return $response; 
    }
    try {
        $fp = fopen("../JSON/mailer.json", 'w');
        fwrite($fp, json_encode($jsonString));
        fclose($fp);
    } catch (\Throwable $th) {
          $response->getBody()->write(static::json($th->__tostring()));
        return $response; 
    }
    $response->getBody()->write(static::json("Votre mail a été envoyer!",[] ,true));
    return $response; 
}
    
}
