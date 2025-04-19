<?php 
require_once "./vendor/autoload.php";
require_once "./modules/imagesAPI/img.php";
require_once "./modules/imagesAPI/getImageFromRoblox.php";
require_once "./modules/imagesAPI/CompareImages.php";
set_time_limit(0);

use Jenssegers\ImageHash\{
    ImageHash,
    Hash
};
use ImageAPI\{
    Img,
    GetImageFromRoblox
};
use Jenssegers\ImageHash\Implementations\DifferenceHash;
$dir = "./images";
$data=[];
$hasher= new ImageHash(new DifferenceHash());
function SetFolders(string $dir , &$data) :void {
    global $hasher;
    foreach (scandir($dir) as $key=> $value) {
        if ($key >1 && is_dir($dir."/".$value)) {
            if (!isset($data[$value])) $data[$value] = [];
            if (count(scandir($dir."/".$value)) > 3) SetFolders($dir."/".$value ,$data[$value]);
        }
        if(preg_match("#\.(jpg|jpeg|png|bmp|tif)$#i", $value)){
            array_push($data , $hasher->hash($dir."/".$value)->toBits());
        } 
    }    
}



SetFolders($dir,$data);
$fp = fopen("./Data/AssetsData.json","w");
fwrite($fp , json_encode($data ));
fclose($fp);

// $file = "./Data/AssetsData.json";
// $fp = fopen($file ,"r");
// $newData =json_decode(fread($fp, filesize($file)));

// $Charge = [];

function SetNewData(&$newData,&$Charge) :void {
    foreach ($newData as $key => $value) {
        if (gettype($value) == "array") {
            $Charge[$key] = [];
            SetNewData($value , $Charge[$key]);
        }
        if (gettype($value)=="string"){
            array_push($Charge , Hash::fromBits($value));
        }
     }
}

SetNewData($newData , $Charge);


// $dict = "./images";
// foreach(scandir($dict) as $fichier){
//     if(preg_match("#\.(jpg|jpeg|png|bmp|tif)$#i", $fichier)){
//         $img = new Img("$dict/$fichier");
//         $img->debug = false;
//         $flip =  $img->GetFlipbookIs();
//         // echo $img->GetFlipbookIs()." <br>";
//         // $img->Show();
//         rename("$dict/$fichier","$dict/$flip/$fichier");
//     }
// }

?>