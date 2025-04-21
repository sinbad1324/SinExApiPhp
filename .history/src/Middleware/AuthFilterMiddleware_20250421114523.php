<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once "../src/Middleware/BaseMiddleware.php";

final class AuthFilterMiddleware extends BaseMiddleware
{
    protected function  Execute(Request $request, RequestHandler $handler): Response
    {
        $data = $request->getAttribute("dataBody");
        // Valeur attandue :    
        /**
         * userName string( min 2 max 50  )
         * email : string (255)
         * password : string(min 8,max25)
         * confirmedPassword : string(min 8,max25)
         * ruleAccepted : bool
         */

        $VCO = $this->VCO($data);
        if (count($VCO) >= 1) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write($this->json("Il y a eu quelque erreurs!", $VCO));
            return $response;
        }
        $errors = $this->Filter($data);
        if (count($errors) >= 1) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write($this->json("Il y a eu quelque erreurs!", $errors));
            return $response;
        }
        return $handler->handle($request);
    }

    /**
     * name: VerificationChampsObligatoir
     * le but de cette function es de verifier si tou les champs sont fournis
     * 
     */
    private function VCO($data): array
    {
        $errors = [];
        if (!isset($data["userName"]))
            array_push($errors, "Le nom d'utilisateur est requis (userName)");
        if (!isset($data["email"]))
            array_push($errors, "L'email est requis (email)");
        if (!isset($data["password"]))
            array_push($errors, "Le mot de passe est requis (password)");
        if (!isset($data["confirmedPassword"]))
            array_push($errors, "La confirmation du mot de passe est requise (confirmedPassword)");
        if (!isset($data["ruleAccepted"]))
            array_push($errors, "Le ruleAccepted est requise (ruleAccepted)");
        return $errors;
    }

    private function VerificationFormat(array $data): array
    {
        $errors = [];
        if (!filter_var($data["ruleAccepted"], FILTER_VALIDATE_BOOL))
            array_push($errors, "Le ruleAccepted doi etre du type boolean!");
        if (!is_string($data["userName"]))
            array_push($errors, "Le userName doit etre du type string");
        if (!is_string($data["email"]))
            array_push($errors, "Le email doit etre du type string");
        if (!is_string($data["confirmedPassword"]))
            array_push($errors, "Le confirmedPassword doit etre du type string");
        if (!is_string($data["password"]))
            array_push($errors, "Le password doit etre du type string");
        return $errors;
    }
    /**
     * Cette function va verfier si nous avons recus tout les donné  puis verifier le type de donéé puis enfin verifier les contraint!
     * @param array $data
     * @return array 
     */
    private function VerificationContraint(array $data): array
    {
        $errors = [];
        if (count($errors) >= 1)
            return $errors; // ici si on a plus que un error on return
        if (!$this->MinMaxStr($data["userName"], 2, 50))
            array_push($errors, "Le nom d'utilisateur doit contenir entre 2 et 50 caractères");
        if (!$this->MinMaxStr($data["email"], 2, 255))
            array_push($errors, "L'email doit contenir entre 2 et 255 caractères");
        if ($data["password"] != $data["confirmedPassword"])
            array_push($errors, "Le mot de passe et sa confirmation ne correspondent pas");
        if (!$this->MinMaxStr($data["password"], 8, 25))
            array_push($errors, "Le mot de passe doit contenir entre 8 et 25 caractères");
        if (!preg_match("/[0-9]/", $data["password"]))
            array_push($errors, "Le mot de passe doit contenir au moins un chiffre");
        if (!preg_match("/[A-Z]/", $data["password"]))
            array_push($errors, "Le mot de passe doit contenir au moins une majuscule");
        if (!preg_match("/[a-z]/", $data["password"]))
            array_push($errors, "Le mot de passe doit contenir au moins une minuscule");
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
            array_push($errors, "Le email n'est pas valide!");
        if (preg_match('/\s+/', $data["userName"]))
            array_push($errors, "Le nom ne doit pas contenire des espace");
        if (preg_match('/\s+/', $data["password"]))
            array_push($errors, "Le Password ne doit pas contenire des espace");
        return $errors;
    }

    private function MinMaxStr(string $str, int $min, int $max)
    {
        $nameCount = strlen($str);
        if ($nameCount > 2 && $nameCount <= 50)
            return true;
        return false;
    }
}
