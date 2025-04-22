<?php

namespace src\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\ImageAPI\ImageService;

require_once "../src/Services/imagesAPI/ImageService.php";


final class ImageAPIController extends BaseController
{
   
   function PostImageAllInfo(Request $request, Response $response, $args): Response
   {
      $dataBody = $request->getAttribute("dataBody");
      if (!isset($dataBody["images"])) {
         $response->getBody()->write(static::json("Il nous manque les images pour pouvoir faire l'opération!"));
         return $response;
      }
      if (count($dataBody["images"])>50) {
         $response->getBody()->write(static::json("Pas plus que 50 images please!"));
         return $response;
      }
         //voir si il y a plus que 50 images.
      $loadedData =  $this->container->get("loadedData");
      $data = ImageService::GetImageAllInfo($dataBody["images"],$loadedData);
      $response->getBody()->write(static::json("Voici les données:",$data,true));
      return $response;
   }

   function PostImageAllInfoWithRobloxId(Request $request, Response $response, $args): Response
   {
      $dataBody = $request->getAttribute("dataBody");
      if (!isset($dataBody["assetIds"])) {
         $response->getBody()->write(static::json("Il nous manque le assets pour pouvoir faire l'opération!"));
         return $response;
      }
      //voir si il y a plus que 50 images.
      if (count($dataBody["assetIds"])>50) {
         $response->getBody()->write(static::json("Pas plus que 50 images please!"));
         return $response;
      }
      $loadedData =  $this->container->get("loadedData");
      $dataImages = ImageService::GetImageAllInfoWithAssets($dataBody["assetIds"],$loadedData);
      $response->getBody()->write(static::json("Voici les données: ",$dataImages,true));
      return $response;
   }
}
