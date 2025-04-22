<?php

namespace src\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\ImageAPI\Img;
final class ImageAPIController extends BaseController
{
   function GetImageAllInfo(Request $request, Response $response, $args) : Response {
    $response->getBody()->write("yo");
    // new Img();
    return $response;
   }
}
