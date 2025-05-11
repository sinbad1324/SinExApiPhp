<?php

namespace Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Services\JWT\JWTService;

use function DI\get;

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

        // régle le JWT toujour dans le header
        $headerJWT = $request->getHeaders()["JWT"][0];
  
        try{

            if (!$headerJWT) {
                $response->getBody()->write(static::json("Il manque le JWT!".$headerJWT));
                return $response;
            }
            // verifier que le utiisateur a un jwt
            if (!isset($headerJWT) || !is_string($headerJWT) || strlen($headerJWT)<1) {
                $response->getBody()->write(static::json("Vous n'avez pas de JWT vous ne pouvez donc pas vous connecté!",[]));
                return $response;
            }
            $parts = explode('.', $headerJWT);
            if (count($parts)!=3) {
                $response->getBody()->write(static::json("Votre JWT n'est pas valide!"));
                return $response;
            }
            $jwt = JWTService::VerifieJWTTemps($headerJWT);
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
        }catch(\Exception $e){
            $response->getBody()->write(static::json("Il y a eu une erreur: ".$e,[]));
            return $response;
        }
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
