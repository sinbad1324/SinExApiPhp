<?php

use Middleware\FilterMiddleware;
use Middleware\JWTVerificationMiddelware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;

require_once "../src/Middleware/FilterMiddleware.php";
require_once "../src/Middleware/JWTVerificationMiddelware.php";

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

//DEfine a new middleware;
$app->add(JWTVerificationMiddelware::class);
$app->add(FilterMiddleware::class);
$app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app): ResponseInterface {
    if ($request->getMethod() === 'OPTIONS') {
        $response = $app->getResponseFactory()->createResponse();
    } else {
        $response = $handler->handle($request);
    }

    $response = $response
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->withHeader('Pragma', 'no-cache');

    if (ob_get_contents()) {
        ob_clean();
    }

    return $response;
});
?>