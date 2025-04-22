<?php 
// creation dun container
// $container = $app->getContainer();

//Deffinir un constant qui sera une class pdo de puis la class singleton Connection comment parametre SinEx le nom de la base de donné

use Services\ImageAPI\ImageService;

require_once "../src/Services/imagesAPI/ImageService.php";



$container->set('loadedData', function () {
   return ImageService::LoadData();
});
?>