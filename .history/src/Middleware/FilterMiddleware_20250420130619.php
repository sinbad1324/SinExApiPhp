<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once "../src/Middleware/BaseMiddleware.php";

final class FilterMiddleware extends BaseMiddleware
{
    protected function  Execute(Request $request, RequestHandler $handler): Response
    {
            // $response = $this->responseFactory->createResponse();
            // $response->getBody()->write("Yo is my Middleware");
            // extraire les données
            $dataQuery = $request->getUri()->getQuery(); 
            $dataBody = json_decode($request->getBody()->getContents() ?? "" , true);
            //filtrer les donné
            
            // mettre les donné pour le prochaine route
            $request->withAttribute("dataQuery" , $dataQuery);
            $request->withAttribute("dataBody" , $dataBody);
           
        return $handler->handle($request);
    }
}
