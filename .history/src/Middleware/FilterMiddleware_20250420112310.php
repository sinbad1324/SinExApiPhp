<?php 
namespace Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
final class FilterMiddleware {
  function __invoke(Request $request, RequestHandler $handler)
  {

  }
}


?>