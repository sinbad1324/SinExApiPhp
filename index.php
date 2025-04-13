<style>
    body {
        background-color: burlywood;
    }
</style>
<?php
//Includes
require "./vendor/autoload.php";
include_once "./modules/imagesAPI/img.php";
include_once "./modules/imagesAPI/getImageFromRoblox.php";
include_once "./modules/imagesAPI/CompareImages.php";

//NameSpace
use ImageAPI\{
    Img,
    GetImageFromRoblox,
    Comparator
};
use Jenssegers\ImageHash\Hash;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use function ImageAPI\GetImageFromRoblox;
Comparator::init();
//Codes
$assets = [
    "9563945564",
    "14133763772",
    "8037357501",
    "85403020583447",
    "16877946118",
    "16981055058",
    "16322490180",
    "16879797476",
    "13829407968",
    "109440284900004",
];
// for ($i = 0; $i < count($assets); $i++) {
//     $data = GetImageFromRoblox($assets[$i]);
//     if ($data != null) {
//         $img = new Img($data["image"], $data["id"]);
//         echo $img->GetFlipbookIs() . "  ".$data["id"]." <br>";
//         $img->Show();
//     }
// }

// $img = new Img("images/6.png");
// echo $img->GetFlipbookIs() . "  ".$data["id"]." <br>";
// $img->Show();

// $img2 = new Img("images/IMPACTTT_00000.png");
// // echo $img2->GetFlipbookIs() . "  ".$data["id"]." <br>";
// // $img2->Show();

// $Hasher = new ImageHash(new DifferenceHash());
// $hash1 = $Hasher->hash($img->image);
// $hash2 = $Hasher->hash($img2->image);
// echo "Similarity->".Comparator::CreateComparate($hash1,$hash2);
$f="2x2";
$dict = "./AssetsData/$f";
foreach(scandir($dict) as $fichier){
    if(preg_match("#\.(jpg|jpeg|png|bmp|tif)$#i", $fichier)){
        $img = new Img("$dict/$fichier");
        // $img->debug = false;
        // $flip =  $img->GetFlipbookIs();
        echo $img->GetFlipbookIs()." <br>";
        $img->Show();
        // rename("$dict/$fichier","$dict/$flip/$fichier");
    }
}

?>