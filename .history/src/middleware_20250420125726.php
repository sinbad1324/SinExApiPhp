<?php

use Middleware\FilterMiddleware;
require_once "../src/Middleware/FilterMiddleware.php";

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

//DEfine a new middleware;
$app->add(FilterMiddleware::class)

?>