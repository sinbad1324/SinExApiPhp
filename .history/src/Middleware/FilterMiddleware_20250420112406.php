<?php 
namespace Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class FilterMiddleware implements MiddlewareInterface {
  function process(Request $request, RequestHandler $handler)
  {

  }
}



?>