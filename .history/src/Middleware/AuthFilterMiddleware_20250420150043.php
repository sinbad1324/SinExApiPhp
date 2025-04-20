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
            var_dump($request->getAttribute("dataBody"));

            return $handler->handle($request);
    }

    private function Filter(array $data) {
        
    }
}
