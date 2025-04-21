<?php

namespace src\Controllers;

use Services\Auth\GoogleAuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\Auth\ManuelAuthServices;

require_once "../src/Services/Auth/ManuelAuthServices.php";
require_once "../src/Services/Auth/GoogleAuthService.php";

final class AuthController extends BaseController
{
    public  function PostRegister(Request $request, Response $response, $args)
    {
        //Data se sont des donné propre nétoyer
        $data = $request->getAttribute('dataBody');
        $newResponse = ManuelAuthServices::RegitreNewUser($data);
        $response->getBody()->write($newResponse);
        return $response;
    }

    /**
     * Ici c'est juste un controller qui es sensé recevoir la request puis le traité avec ce que le service nous propose
     */
    public  function GetVerifieCode(Request $request, Response $response, $args)
    {
        $data = $request->getAttribute('dataQuery'); // prendre les donnée depuis le middelware qui a nétoyer tout cela
        // var_dump($data);
        /**
         * Data a recevoir :
         * int $id 
         * string $code size 6
         *  
         */
        //Data se sont des donné propre nétoyer
        
        $response->getBody()->write("Voutre est verifier vouliez vous vous connecter!");
        return $response;
    }

    public  function GetRemakeNewCode(Request $request, Response $response, $args)
    {
        //Data se sont des donné propre nétoyer
        $data = $request->getAttribute('dataBody');
        $newResponse = ManuelAuthServices::RegitreNewUser($data);
        $response->getBody()->write($newResponse);
        return $response;
    }

    public function GetGoogleConnectionURL(Request $request, Response $response, $args)
    {
        $response->getBody()->write($this->json("url form google OAuth2", ["url" => GoogleAuthService::GetURLForClient()], true));
        return $response;
    }

    public function GetGoogleConnectionData(Request $request, Response $response, $args)
    {
        return $response;
    }
}
