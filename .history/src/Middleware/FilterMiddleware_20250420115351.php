<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once "../src/Middleware/BaseMiddleware.php";

final class FilterMiddleware extends BaseMiddleware
{
    protected function  Execute(Request $request, RequestHandler $handler) : Response | null{
        # si on return null il laisse passe au prochain mais si on return une response d'u doup ca va nous retunr une response et non accé au router.
        if (true) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write("Yo is my Middleware");
            return null;
        }
        return null;
    }
}
