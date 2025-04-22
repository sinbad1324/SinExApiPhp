<?php

use Middleware\FilterMiddleware;
use Middleware\JWTVerificationMiddelware;

require_once "../src/Middleware/FilterMiddleware.php";
require_once "../src/Middleware/JWTVerificationMiddelware.php";

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

//DEfine a new middleware;
$app->add(JWTVerificationMiddelware::class);
$app->add(FilterMiddleware::class);

?>