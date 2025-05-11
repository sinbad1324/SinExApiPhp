<?php

namespace Services\ImageAPI;

require_once "../src/Services/imagesAPI/getImageFromRoblox.php";
require_once "../src/Services/imagesAPI/img.php";

use Services\ImageAPI\{Img, GetImageFromRoblox};
use Jenssegers\ImageHash\{
    Hash
};

final class ImageService
{
    public static function GetImageAllInfo(&$dataImages, &$loadedData): array
    {
        $images = [];

        foreach ($dataImages as $key => $image) {
            try {
                $decodedImage = base64_decode($image);
                $img = new Img($decodedImage);
                $img->debug = false;

                // $result = $img->GetResult($loadedData);
                // if (!isset($images[$result["categorie"]]))
                //     $images[$result["categorie"]] = [];
                // array_push($images[$result["categorie"]], $result);

                array_push($images, [
                    "grid" => $img->GetFlipbookIs(),
                    "size" => $img->normalSize,
                    "time" => 1,
                    "categorie" => "none",
                    "precision" => 100
                ]);
            } catch (\Exception $e) {
            }
        }
        return $images;
    }


    public static function GetImageAllInfoWithAssets(&$dataImages, &$loadedData): array
    {
        $images = [];
        foreach ($dataImages as $key => $asset) {
            try {
                $imageRessource = \Services\ImageAPI\GetImageFromRoblox($asset);
                if ($imageRessource) {
                    $img = new Img($imageRessource["image"], $imageRessource["id"]);
                    $img->debug = false;
                    // $result = $img->GetResult($loadedData);
                    // if (!isset($images[$result["categorie"]]))
                    //     $images[$result["categorie"]] = [];
                    // array_push($images[$result["categorie"]], $result);
                    array_push($images, [
                        "grid" => $img->GetFlipbookIs(),
                        "size" => $img->normalSize,
                        "time" => 1,
                        "categorie" => "none",
                        "precision" => 100
                    ]);
                }
            } catch (\Exception $e) {
            }
        }
        return $images;
    }


    public static function LoadData(): array
    {
        $file = "../Data/AssetsData.json";
        $fp = fopen($file, "r");
        $newData = json_decode(fread($fp, filesize($file)));
        $Charge = [];
        function SetNewData(&$newData, &$Charge): void
        {
            foreach ($newData as $key => $value) {
                if (gettype($value) == "array") {
                    $Charge[$key] = [];
                    SetNewData($value, $Charge[$key]);
                }
                if (gettype($value) == "string") {
                    array_push($Charge, Hash::fromBits($value));
                }
            }
        }
        SetNewData($newData, $Charge);
        return $Charge;
    }
}
