<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseFactoryInterface;

final class FilterMiddleware implements MiddlewareInterface
{

    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Cette function sera appellé quand le middleware sera executé 
     * si notre condition est remplis en envoie une reponse sinon on pass au prochain avec ($handler->handle($request);)
     * @param Request $request
     * @param RequestHandler $request
     * @return Response
     * 
     */
    function process(Request $request, RequestHandler $handler): Response {


        return $handler->handle($request);
    }
}
