<?php 
require_once "./vendor/autoload.php";

use Jenssegers\ImageHash\{
    ImageHash,
    Hash
};
use Jenssegers\ImageHash\Implementations\DifferenceHash;
$dir = "./AssetsData";
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


// SetFolders($dir,$data);
// $fp = fopen("./Data/AssetsData.json","w");
// fwrite($fp , json_encode($data ));
// fclose($fp);

$file = "./Data/AssetsData.json";
$fp = fopen($file ,"r");
$newData =json_decode(fread($fp, filesize($file)));

$Charge = [];

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

// SetNewData($newData , $Charge);

// var_dump($Charge);

?>