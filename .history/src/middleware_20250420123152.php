<?php

use Middleware\FilterMiddleware;
require_once "../src/Middleware/FilterMiddleware.php";

$app->addBodyParsingMiddleware();

//DEfine a new middleware;
$app->add(FilterMiddleware::class)

?>