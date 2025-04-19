<?php
//Namespace
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;
//Require/Includes
require_once "../vendor/autoload.php";
require_once "../src/Controllers/BaseController.php";
require_once "../src/settings.php";
require_once "../src/constants.php";
//Codes
$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
//Routes requires
require_once "../src/content.php";
require_once "../src/routes.php";
$app->run();

?>