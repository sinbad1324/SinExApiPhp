<?php
//Namespace
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
//Require/Includes
require_once "../vendor/autoload.php";
require_once "../src/Controllers/BaseController.php";
require_once "../src/settings.php";
require_once "../src/content.php";
require_once "../src/constants.php";
//Codes
$app = AppFactory::create();
//Routes requires
include_once "../src/routes.php";
$app->run();

?>