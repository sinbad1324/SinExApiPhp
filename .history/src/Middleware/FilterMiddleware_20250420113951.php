<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseFactoryInterface;

require_once "../src/Middleware/BaseMiddleware.php";

final class FilterMiddleware extends BaseMiddleware
{
    protected function  Execute(Request $request, RequestHandler $handler) : Response | null{
        if (true) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write("Yo is my Middleware");
            return $response;
        }
        return null;
    }
}
