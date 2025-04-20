<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once "../src/Middleware/BaseMiddleware.php";

final class FilterMiddleware extends BaseMiddleware
{
    protected function  Execute(Request $request, RequestHandler $handler) : Response {
        if (true) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write("Yo is my Middleware");
            $uri = $request->getUri()->getQuery();
            var_dump($request->getBody());
            return $handler->handle($request);
        }
        return $handler->handle($request);
    }
}
