<?php 
namespace src\Controllers;
use Services\Auth\GoogleAuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
require_once "../src/Services/auth/GoogleAuthService.php";

final class AuthController extends BaseController{
    public  function PostRegister(Request $request, Response $response, $args){
        $response->getBody()->write($this->json("Succes" , [] , true));
        return $response;
    }

    public function GetGoogleConnectionURL(Request $request, Response $response, $args) {
        $response->getBody()->write($this->json("url form google OAuth2" , ["url"=>GoogleAuthService::GetURLForClient()], true));
        return $response;
    }

    public function GetGoogleConnectionData(Request $request, Response $response, $args) {
        return $response;
    }
}
?>