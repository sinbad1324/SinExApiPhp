<?php
namespace  ImageAPI;
use Http\Request;
include_once "./modules/http/request.php";
/**
 * 
 * This function return a array with a ressource image from roblox server if the image dont existe or need a login the functione will return null or array(2) ["image"=>ressource , "id"=>string]
 * @param string 
 * @return array|null 
 */
function GetImageFromRoblox(string $assetId) : array | null  {
    $data = Request::Fetch("https://assetdelivery.roblox.com/v2/assetId/$assetId") ;
    $data = json_decode($data,true);
    $originalId = $assetId;
    // var_dump($data);
    // echo "<br>";
    if (!isset($data["errors"])) {
        if (isset($data["locations"])) {
           if (isset($data["locations"][0]["location"])) {
                // $newData = Request::Fetch($data["locations"][0]["location"]);
                // var_dump($newData);
                return ["image"=>fopen($data["locations"][0]["location"] , 'rb'),"id"=>$originalId];
                // var_dump($newData);
           }
        }
        
    }
    return null;
}

?>