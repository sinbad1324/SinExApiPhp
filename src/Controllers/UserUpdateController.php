<?php

namespace src\Controllers;

use Models\UserModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\Auth\UpdatePasswordService;
require_once "../src/Services/Updates/UpdatePasswordService.php";

require_once "../src/Models/UserModel.php";

final class UserUpdateController extends BaseController
{
    public  function PutUpdateName(Request $request, Response $response, $args)
    {
        $payload = $request->getAttribute("payload");
        if ($payload["userName"] == $args["name"]) {
            $response->getBody()->write(static::json("Votre nom n'a pas changer!"));
            return $response;
        }
        $filtred = $this->FilterUserName($args);
        if (gettype($filtred) == "string") {
            $response->getBody()->write($filtred);
            return $response;
        }
        $name = $filtred["name"];
        if (UserModel::UpdateUserName($name, $payload["id"])) {
            $response->getBody()->write(static::json("Votre nom (" . $payload['userName'] . ") par $name!", [], true));
            return $response;
        }
        $response->getBody()->write(static::json("Nous avons eu un probléme ressayer plus tard!"));
        return $response;
    }

    /**
     * Filtrer les donné pour changer de userName
     * @param array
     * @return array|string
     */
    private function FilterUserName(array $args): array | string
    {
        if (!isset($args["name"]))
            return static::json("le nouveau nom n'est pas fournis!!", []);

        $name = filter_var($args["name"], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!is_string($name) || !static::ClampString($name, 2, 50)) return static::json("le nouveau nom doit etre du type text et etre entre 2 et 50 char!", []);
        if (preg_match('/\s+/', $name)) return static::json("Le nom ne doit pas contenire des espace", []);
        if (UserModel::FindWithName($name)) return static::json("le nouveau nom existe déja!", []);
        if ($name != $args["name"]) return static::json("ce nom conient des char non acceptable!", []);
        return ["name" => $name];
    }

    public  function PutUpdateEmail(Request $request, Response $response, $args)
    {
        $payload = $request->getAttribute("payload");
        $dataBody = $request->getAttribute("dataBody");
        $filtred = $this->FilterEmail($dataBody);
        if (gettype($filtred) == "string") {
            $response->getBody()->write($filtred);
            return $response;
        }
        $email = $filtred["email"];
        if (UserModel::UpdateUserEmail($email, $payload["id"])) {
            $response->getBody()->write(static::json("Votre email a été remplacer par $email!", [], true));
            return $response;
        }
        $response->getBody()->write(static::json("Nous avons eu un probléme ressayer plus tard!"));
        return $response;
    }

    /**
     * Filtrer les donné pour changer de Email
     * @param array
     * @return array|string
     */
    private function FilterEmail(array $args): array | string
    {
        if (!isset($args["email"])) return static::json("le nouveau email n'est pas fournis!!", []);
        $email = filter_var($args["email"], FILTER_SANITIZE_SPECIAL_CHARS);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return static::json("le nouveau email n'est pas valide!", []);
        if (!is_string($email) || !static::ClampString($email, 5, 255)) return static::json("le nouveau email doit etre du type text et etre entre 3 et 255 char!", []);
        if (UserModel::FindWithEmail($email)) return static::json("le nouveau email existe déja!", []);
        if ($email != $args["email"]) return static::json("ce email conient des char non acceptable!", []);
        return ["email" => $email];
    }

    public  function PostCodeGenerator(Request $request, Response $response, $args)
    {
        $payload = $request->getAttribute("payload");
        $ServiceResponse = UpdatePasswordService::GenetateNewCode($payload["id"]);
        if ($ServiceResponse) {
            $response->getBody()->write($ServiceResponse);
            return $response;
        }
        $response->getBody()->write(static::json("Nous avons rencontré un probléme vouillez ressayer plus tard!"));
        return $response;
    }

    public  function PostChangePassword(Request $request, Response $response, $args)
    {
        $payload = $request->getAttribute("payload");
        $dataBody = $request->getAttribute("dataBody");
        
        $FilterChangePassword = $this->FilterChangePassword($dataBody);
        if ($FilterChangePassword) {
            $response->getBody()->write($FilterChangePassword);
            return $response;
        }
        
        $verification = $this->VerifiePassword($dataBody);
        if (count($verification) >0) {
            $response->getBody()->write(static::json("Nous avons rencontré un probléme!" , $verification));
            return $response;
        }
        $serviceResponse=UpdatePasswordService::VerifieCode($payload["id"],$dataBody["code"],$dataBody["password"]);
        if ($serviceResponse) {
            $response->getBody()->write($serviceResponse);
            return $response;
        }
        $response->getBody()->write(static::json("Nous avons rencontré un probléme vouillez ressayer plus tard!"));
        return $response;
    }

    private function FilterChangePassword($data):string|null{
        if (!isset($data["code"])) return static::json("Il manque le code pour verifier que c'est bien vous!");
        if (!isset($data["password"]))return static::json("Il manque le nouveau password!");
        if (!isset($data["confirmedPassword"]))return static::json("Il manque a Confirme password!");
        return null;
    }

    private function VerifiePassword($data) : array {
        $errors =[];
        if ($data["password"] != $data["confirmedPassword"])
            array_push($errors, "Le mot de passe et sa confirmation ne correspondent pas");
        if (!$this->ClampString($data["password"], 8, 25))
            array_push($errors, "Le mot de passe doit contenir entre 8 et 25 caractères");
        if (!preg_match("/[0-9]/", $data["password"]))
            array_push($errors, "Le mot de passe doit contenir au moins un chiffre");
        if (!preg_match("/[A-Z]/", $data["password"]))
            array_push($errors, "Le mot de passe doit contenir au moins une majuscule");
        if (!preg_match("/[a-z]/", $data["password"]))
            array_push($errors, "Le mot de passe doit contenir au moins une miniscule");
        if (preg_match('/\s+/', $data["password"]))
            array_push($errors, "Le Password ne doit pas contenire des espace");
        return $errors;
    }
    
}
