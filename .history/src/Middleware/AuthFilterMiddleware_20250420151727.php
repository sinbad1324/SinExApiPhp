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
        $nameCount =strlen($data["userName"]);
        if ($nameCount < 2 && $nameCount > 50) {
            return $this->json("User name have to (lengt min 2 max 50)" , []);
        }
        $nameCount =strlen($data["email"]);

    }
}
