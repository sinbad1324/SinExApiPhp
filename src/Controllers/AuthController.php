<?php 
namespace src\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class AuthController extends BaseController{
    public  function register(Request $request, Response $response, $args){
       
        $response->getBody()->write("wefwefew");
        return $response;
    }
}
?>