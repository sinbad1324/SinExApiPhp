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
        //Data se sont des donné propre nétoyer
        $data = $request->getAttribute('dataQuery');
        if (!$data) 
            $response->getBody()->write($this->json("Nous avons pas recus de données!", $data));
            return $response;
        
        // Verifier si on recus tout les donné car il n'y a pas de maiddelware pour verifier cela:
        if (!isset($data["id"])) 
            $response->getBody()->write($this->json("Nous avons pas recus le id!", $data));
            return $response;
        if (!isset($data["code"])) 
            $response->getBody()->write($this->json("Nous avons pas recus le code!", $data));
            return $response;
        // Verifier les contraint car il n'y a pas de maiddelware pour verifier cela:
        if (filter_var($data["id"], FILTER_VALIDATE_INT)) 
            $response->getBody()->write($this->json("Le id n'est pas valide!", $data));
            return $response;
        if (is_string($data["code"]) && $this->ClampString($data["code"], 5, 6)) 
            $response->getBody()->write($this->json("Ce code n'est pas valide!", $data));
            return $response;
        
        // logiques 
        $message= ManuelAuthServices::VerifieCode($data["code"] , $data["id"]);
        if ($message) {
            $response->getBody()->write($message);
            return $response;
        }
        /**
         * Data a recevoir :
         * int $id 
         * string $code size 6
         *  */
        var_dump($data);
        // $newResponse = ManuelAuthServices::($data);
        $response->getBody()->write("newResponse");
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
