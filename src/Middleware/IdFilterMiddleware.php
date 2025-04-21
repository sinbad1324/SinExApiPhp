<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once "../src/Middleware/BaseMiddleware.php";

final class IdFilterMiddleware extends BaseMiddleware
{
    protected function  Execute(Request $request, RequestHandler $handler): Response
    {
            // extraire les données
            $dataQuery = $request->getQueryParams(); 
            $dataBody = json_decode($request->getBody()->getContents() ?? "" , true);
            //filtrer les donné
            $methode = $request->getMethod();
            if ($methode == "POST") {
              $res= $this->IdFilter($dataBody ?? []);
              if ($res){
                $response = $this->responseFactory->createResponse();
                $response->getBody()->write($res);
                return $response;
            } 
            }else if ($methode == "GET") {
                $res= $this->IdFilter($dataQuery ?? []);
                if ($res){
                    $response = $this->responseFactory->createResponse();
                    $response->getBody()->write($res);
                    return $response;
                } 
              }            
            // mettre les donné pour le prochaine route
            // withAttribute return une nouvelle instance de Request
            $request=$request
            ->withAttribute('dataQuery' , $dataQuery)
            ->withAttribute('dataBody' , $dataBody);
        return $handler->handle($request);
    }

    private function IdFilter(array $data):string | null {
        if ($data) {
            if (!isset($data["id"])) 
                return $this->json("Nous avons pas recus le id!", $data);
            if (!filter_var($data["id"], FILTER_VALIDATE_INT)==true) 
                return $this->json("Le type du id n'est pas valide!", $data);
        }
        return null;
    }
}
