<?php
//Namespace

use Middleware\AuthFilterMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\Controllers\AuthController;
//requires
require_once "../src/Controllers/AuthController.php";
require_once "../src/Middleware/AuthFilterMiddleware.php";
//Routes
//Sign up route methodes post 
$app->post(ENTRY_POINT . '/auth/register', AuthController::class . ":PostRegister")//->add(AuthFilterMiddleware::class);
$app->get(ENTRY_POINT . '/auth/google-connection', AuthController::class . ":GetGoogleConnectionURL");
$app->get(ENTRY_POINT . '/auth/google-get-connection', AuthController::class . ":GetGoogleConnectionData");

?>