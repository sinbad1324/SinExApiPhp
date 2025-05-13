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
    // ManuelAuth
    public  function PostRegister(Request $request, Response $response, $args)
    {
        //Data se sont des donné propre nétoyer
        $data = $request->getAttribute('dataBody');
        if ($data["ruleAccepted"] == false) {
            $response->getBody()->write(static::json("Il faut accepter les regle d'utilisation pour pouvoir vous inscrir!", []));
            return $response;
        }
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
         */
        //Data se sont des donné propre nétoyer
        if (!$data) {
            $response->getBody()->write($this->json("Nous avons pas recus de données!", $data));
            return $response;
        }
        // Verifier si on recus tout les donné car il n'y a pas de maiddelware pour verifier cela:
        if (!isset($data["code"])) {
            $response->getBody()->write($this->json("Nous avons pas recus le code!", $data));
            return $response;
        }
        // Verifier les contraint car il n'y a pas de maiddelware pour verifier cela:
        if (!is_string($data["code"]) || !$this->ClampString($data["code"], 5, 6)) {
            $response->getBody()->write($this->json("le type du code n'est pas valide!", $data));
            return $response;
        }
        // logiques 
        $message = ManuelAuthServices::VerifieCode($data["code"], $data["id"]);
        if ($message) {  // si il y un message c'es qu'il y une erreur 
            $response->getBody()->write($message);
            return $response;
        }
        $response->getBody()->write(static::json("Voutre est verifier vouliez vous vous connecter!", [], true));
        return $response;
    }

      /**
     * Ici c'est juste un controller qui es sensé recevoir la request puis le traité avec ce que le service nous propose
     */
    public  function GetDoubleVerifieCode(Request $request, Response $response, $args)
    {
        $data = $request->getAttribute('dataQuery'); // prendre les donnée depuis le middelware qui a nétoyer tout cela
        // var_dump($data);
        /**
         * Data a recevoir :
         * int $userId 
         * string $code size 6
         */
        //Data se sont des donné propre nétoyer
        if (!$data) {
            $response->getBody()->write($this->json("Nous avons pas recus de données!", $data));
            return $response;
        }
        // Verifier si on recus tout les donné car il n'y a pas de maiddelware pour verifier cela:
        if (!isset($data["code"])) {
            $response->getBody()->write($this->json("Nous avons pas recus le code!", $data));
            return $response;
        }
        // if (!isset($data["userId"])) {
        //     $response->getBody()->write($this->json("Nous avons pas recus le userId!", $data));
        //     return $response;
        // }
        // Verifier les contraint car il n'y a pas de maiddelware pour verifier cela:
        if (!is_string($data["code"]) || !$this->ClampString($data["code"], 5, 6)) {
            $response->getBody()->write($this->json("le type du code n'est pas valide!", $data));
            return $response;
        }
        // logiques 
        $message = ManuelAuthServices::VerifieDoubleAuth($data);
        if ($message) {  
            $response->getBody()->write($message);
            return $response;
        }
        $response->getBody()->write(static::json("Vous etesConnectrl!", [], true));
        return $response;
    }
    /**
     * Cette methodes permet de recréé un nouveau code pour un utilisateur
     */
    public  function GetRemakeNewCode(Request $request, Response $response, $args)
    {
        //Data se sont des donné propre nétoyer
        $data = $request->getAttribute('dataQuery');
        $newResponse = ManuelAuthServices::RemakeVerificationCode($data["id"]);
        $response->getBody()->write($newResponse);
        return $response;
    }
    /**
     * Valider un utilisateur
     */
    public function PostConnection(Request $request, Response $response, $args)
    {
        $data = $request->getAttribute('dataBody');
        //Verifier les donnée
        if (!isset($data["email"]))
            return $response->getBody()->write(static::json("Il manque le email!", []));
        if (!isset($data["password"]))
            return $response->getBody()->write(static::json("Il manque le password!", []));
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
            return $response->getBody()->write(static::json("Le mail n'est pas valide!", []));
        //Valider la connection
        $response->getBody()->write(ManuelAuthServices::Connection($data));
        return $response;
    }
    // Google Auht
    public function GetGoogleConnectionURL(Request $request, Response $response, $args)
    {
        $data = $request->getAttribute('dataQuery');
        if (!isset($data["ruleAccepted"])) {
            $response->getBody()->write(static::json("Pour vous inscrir il faut accepter les régles!", []));
            return $response;
        }
        if (!filter_var($data["ruleAccepted"] ,FILTER_VALIDATE_BOOL)) {
            $response->getBody()->write(static::json("Ce n'est pas le bon type de donnée que nous attendion!", []));
            return $response;
        }
        if ($data["ruleAccepted"] == false) {
            $response->getBody()->write(static::json("Il faut accepter les regle d'utilisation pour pouvoir vous inscrir!", []));
            return $response;
        }
        $newOAuthService = new GoogleAuthService();
        $response->getBody()->write($this->json("url form google OAuth2", ["url" => $newOAuthService->GetURLForClient()], true));
        return $response;
    }

    public function GetGoogleConnectionData(Request $request, Response $response, $args)
    {
        $data = $request->getAttribute('dataQuery');
        //voir les donné
        if (!$data)
            $response->getBody()->write(static::json("Les doné ne sont pas fournis!", []));
        if (!isset($data["code"])) {
            $response->getBody()->write(static::json("Le code n'est pas fourinis", []));
            return $response;
        }
        if (!isset($data["scope"])) {
            $response->getBody()->write(static::json("Le scope n'est pas fourinis", []));
            return $response;
        }
        if (!isset($data["prompt"])) {
            $response->getBody()->write(static::json("Le prompt n'est pas fourinis", []));
            return $response;
        }
        if (!isset($data["authuser"])) {
            $response->getBody()->write(static::json("Le authuser n'est pas fourinis", []));
            return $response;
        }

        if ($data["prompt"] == "consent") {
            $newOAuthService = new GoogleAuthService();
            $ServiceResponse=$newOAuthService->Connection($data);
            $response->getBody()->write($ServiceResponse);
        }

        return $response;
    }
}
