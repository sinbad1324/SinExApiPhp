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
            $dataQuery = $request->getQueryParams(); 
            $dataBody = json_decode($request->getBody()->getContents() ?? "" , true);
            //filtrer les donné
            
            $dataBody = $this->Filter($dataBody ?? []);
            $dataQuery=$this->Filter($dataQuery  ?? []);
            
            // mettre les donné pour le prochaine route
            // withAttribute return une nouvelle instance de Request
            $request=$request
            ->withAttribute('dataQuery' , $dataQuery)
            ->withAttribute('dataBody' , $dataBody);
        return $handler->handle($request);
    }

    private function Filter(array $data):array {
        $newData=[];
        if ($data) {
            foreach ($data as $key => $value) {
                if (gettype($value)=="array") {
                    $newData[$key] = $this->Filter($value);
                }elseif (gettype($value)=="string")
                    $newData[$key]=filter_var($value , FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return  $newData;
    }
}
