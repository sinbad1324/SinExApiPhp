<?php
namespace  ImageAPI;

function GetImageFromRoblox(string $assetId)  {
    $curl = curl_init("https://assetdelivery.roblox.com/v2/assetId/{$assetId}");
    print-r($curl)

}

?>