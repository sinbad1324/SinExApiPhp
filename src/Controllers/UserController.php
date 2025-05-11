<?php

namespace src\Controllers;

use Models\UserModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once "../src/Models/UserModel.php";

final class UserController extends BaseController
{
    public  function GetProfile(Request $request, Response $response, $args)
    {
        $payload = $request->getAttribute("payload");

        $user = UserModel::FindWithId($payload["id"]);
        if (!$user) {
            $response->getBody()->write(static::json("Cette user n'existe pas!"));
            return $response;
        }
        $response->getBody()->write(static::json("Voici le profile!", ["profile" => [
            "userId" => $user["userId"],
            "userName" => $user["userName"],
            "email" => $user["email"],
        ]], true));
        return $response;
    }
}
