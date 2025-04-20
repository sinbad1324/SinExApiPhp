<?php
namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once "../src/Middleware/BaseMiddleware.php";

final class AuthFilterMiddleware extends BaseMiddleware
{
    protected function  Execute(Request $request, RequestHandler $handler): Response
    {
            // $response = $this->responseFactory->createResponse();
            // $response->getBody()->write("Yo is my Middleware");
            $data = $request->getAttribute("dataBody");
            var_dump($data);
            // Valeur attandue :    
            /**
             * userName string( min 2 max 50  )
             * email : string (50)
             * password : string(min 8,max25)
             * confirmedPassword : string(min 8,max25)
             */
            return $handler->handle($request);
    }

    private function Filter(array $data) {
        //Verifier que nous avons recus tout les donnée
        if (!isset($data["userName"])) 
            return $this->json("Where is userName???" , []);
        if (!isset($data["email"])) 
            return $this->json("Where is email???" , []);
        if (!isset($data["password"])) 
            return $this->json("Where is password???" , []);
        if (!isset($data["confirmedPassword"])) 
            return $this->json("Where is confirmedPassword???" , []);
        
        //Verifier que le user name est entre 2 et 50 chars
        if (!$this->MinMaxStr($data["userName"],2,50)) {
            return $this->json("User name have to (lengt min 2 max 50)" , []);
        }
        if (!$this->MinMaxStr($data["email"],2,50)) {
            return $this->json("email have to (lengt min 2 max 50)" , []);
        }
        if ($data["password"] !=$data["confirmedPassword"]) {
            $this->json("confirme password is to the same with confirmed Password" , []);
        }
        if (!$this->MinMaxStr($data["password"],8,25)) {
            return $this->json("email have to (lengt min 2 max 50)" , []);
        }
    }

    private function MinMaxStr(string $str, int $min , int $max)  {
        $nameCount =strlen($str);
        if ($nameCount < 2 && $nameCount > 50) 
            return false;
        return true;
    }
}
