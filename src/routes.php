<?php
//Namespace

use Middleware\AuthFilterMiddleware;
use Middleware\IdFilterMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\Controllers\AuthController;
//requires
require_once "../src/Controllers/AuthController.php";
require_once "../src/Middleware/AuthFilterMiddleware.php";
require_once "../src/Middleware/IdFilterMiddleware.php";

//Routes
//Sign up route methodes post 
$app->post(ENTRY_POINT . '/auth/register', AuthController::class . ":PostRegister")->add(AuthFilterMiddleware::class);
$app->post(ENTRY_POINT . '/auth/connection', AuthController::class . ":PostConnection");
$app->get(ENTRY_POINT . '/auth/remake-new-code', AuthController::class . ":GetRemakeNewCode")->add(IdFilterMiddleware::class);
$app->get(ENTRY_POINT . '/auth/verifie-code', AuthController::class . ":GetVerifieCode")->add(IdFilterMiddleware::class);
//GoogleAuth
$app->get(ENTRY_POINT . '/auth/google-connection', AuthController::class . ":GetGoogleConnectionURL");
$app->get(ENTRY_POINT . '/auth/google-get-connection', AuthController::class . ":GetGoogleConnectionData");

?>