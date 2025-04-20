<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
            $this->Filter($dataBody ?? []);
            $this->Filter($dataQuery  ? json_decode($dataQuery):[]);
            // mettre les donné pour le prochaine route
            // withAttribute return une nouvelle instance de Request
            $request=$request
            ->withAttribute('dataQuery' , $dataQuery)
            ->withAttribute('dataBody' , $dataBody);
        return $handler->handle($request);
    }

    private function Filter(array $data) {
        if ($data) {
            foreach ($data as $key => $value) {
                if (is_string($value)) $value = filter_var($value , FILTER_SANITIZE_SPECIAL_CHARS);
                if (is_string($key))$value = filter_var($key , FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }
}
