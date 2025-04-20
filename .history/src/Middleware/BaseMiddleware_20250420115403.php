<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
 class BaseMiddleware implements MiddlewareInterface
{
    // ceci est juste une class qui nous permet de créé une reponse attenton ca dans la documentation c'est ecrit qu'il faut uiliser une interface mais c'est un class qu'il faut utiliser!!!
    protected ResponseFactory $responseFactory;
    public function __construct(ResponseFactory $responseFactory)
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
        # cette function (createResponse sur la classe responseFactory ) nous permet de créé une réponse
        // $response = $this->responseFactory->createResponse();
        $response = $this->Execute($request , $handler);
        if ($response) {
            return $response;
        }
        return $handler->handle($request); # ceci aussi créé une reponse
    }
    /**
     *         # si on return null il laisse passe au prochain mais si on return une response d'u doup ca va nous retunr une response et non accé au router.

     */
    protected function  Execute(Request $request, RequestHandler $handler) : Response | null {
        return $this->responseFactory->createResponse();
    }
}
