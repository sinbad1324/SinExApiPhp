<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Services\JWT\JWTService;

require_once "../src/Middleware/BaseMiddleware.php";

final class JWTVerificationMiddelware extends BaseMiddleware
{
    protected function  Execute(Request $request, RequestHandler $handler): Response
    {
        $response = $this->responseFactory->createResponse();
            // Ne pas laisser cetin routes
        $routeUrls= $request->getUri()->getPath();
        $ignoreUrls = ["/api/auth/register","/api/auth/connection","/api/auth/remake-new-code","/api/auth/verifie-code","/api/auth/google-connection","/api/auth/google-get-connection"];
        // nous allos laisser passer tout les auth 
        if (in_array($routeUrls , $ignoreUrls)) return $handler->handle($request);

        // régle le JWT toujour dans le body
        $dataBody = $request->getAttribute("dataBody");
        if (!$dataBody || count($dataBody)<1) {
            $response->getBody()->write(static::json("Il manque le body!",[]));
            return $response;
        }
        // verifier que le utiisateur a un jwt
        if (!isset($dataBody["JWT"]) || !is_string($dataBody["JWT"]) || strlen($dataBody["JWT"])<1) {
            $response->getBody()->write(static::json("Vous n'avez pas de JWT vous ne pouvez donc pas vous connecté!",[]));
            return $response;
        }
        $parts = explode('.', $dataBody["JWT"]);
        if (count($parts)!=3) {
            $response->getBody()->write(static::json("Votre JWT n'est pas valide!",[]));
            return $response;
        }
        $jwt = JWTService::VerifieJWTTemps($dataBody["JWT"]);
        if (!$jwt) {
            $response->getBody()->write(static::json("Votre JWT n'est pas valide!",[]));
            return $response;
        }
        $filtred=$this->FilterPayload($jwt);
        if ($filtred) {
            $response->getBody()->write($filtred);
            return $response; 
        }
        $request = $request->withAttribute("payload",$jwt);
        return $handler->handle($request);
    }
    /**
     * Verifier que toutes les donnée sont present
     * puis les néttoyer
     */
    private function FilterPayload(array &$payload) {
        if (!isset($payload["userName"]))return static::json("Le userName n'es pas fournis dans le JWT!");
        if (!isset($payload["id"]))return static::json("Le id n'es pas fournis dans le JWT!");
        if (!isset($payload["exp"]))return static::json("Le exp n'es pas fournis dans le JWT!");
        foreach ($payload as $key => $value) 
            $payload[$key] = filter_var($value ,FILTER_SANITIZE_SPECIAL_CHARS);
        return null;
    }
}
