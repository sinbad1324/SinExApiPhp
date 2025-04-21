<?php 
namespace src\Controllers;
use Services\Auth\GoogleAuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\Auth\ManuelAuthServices;
use Traits\DataFormatTrait;
use Traits\MinMaxStr;
require_once "../src/Services/Auth/ManuelAuthServices.php";
require_once "../src/Services/Auth/GoogleAuthService.php";
require_once "../src/Traits/MinMaxStr.php";

final class AuthController extends BaseController{
    use MinMaxStr;
    public  function PostRegister(Request $request, Response $response, $args){
        //Data se sont des donné propre nétoyer
        $data= $request->getAttribute('dataBody');
        $newResponse = ManuelAuthServices::RegitreNewUser($data);
        $response->getBody()->write($newResponse);
        return $response;
    }

    /**
     * Ici c'est juste un controller qui es sensé recevoir la request puis le traité avec ce que le service nous propose
     */
    public  function GetVerifieCode(Request $request, Response $response, $args){
        //Data se sont des donné propre nétoyer
        $data= $request->getAttribute('dataQuery');
        if (!$data) {
            $response->getBody()->write("newResponse");
            return $response;
        }
        // Verifier les contraint car il n'y a pas de maiddelware pour verifier cela:
        if () {
            # code...
        }
        /**
         * Data a recevoir :
         * int $id 
         * string $code size 6
         *  */ 
        var_dump($data);
        // $newResponse = ManuelAuthServices::($data);
        $response->getBody()->write("newResponse");
        return $response;
    }

    public  function GetRemakeNewCode(Request $request, Response $response, $args){
        //Data se sont des donné propre nétoyer
        $data= $request->getAttribute('dataBody');
        $newResponse = ManuelAuthServices::RegitreNewUser($data);
        $response->getBody()->write($newResponse);
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