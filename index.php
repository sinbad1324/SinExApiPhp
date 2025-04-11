<?php

require "./vendor/autoload.php";

use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

// $img= new Imagick('images/lum.avif');
// $img->setImageBackgroundColor('black');
// $img = $img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
// $img->setImageColorspace(Imagick::COLORSPACE_GRAY);
// $img->thresholdImage(.05 * Imagick::getQuantumRange()['quantumRangeLong']); 

// $hasher = new ImageHash(new DifferenceHash());
// $hash1 = $hasher->hash('images/lum.avif');
// $hash2 = $hasher->hash('images/lum.avif');

// $hasher->distance($hash1 , $hash2);
// echo $hash1;
// echo $img->getImageWidth()."x".$img->getImageHeight()."<br>";
// for ($i=0; $i < 200 ; $i++) { 
//     $pixel = $img->getImagePixelColor($i,192);
//     print_r($pixel->getColor());
//     $data[$i]=$pixel->getColor();
// }
// var_dump( $data);
// // $base64 = base64_encode($img);

?>
<!-- <img src="data:image/jpg;base64,<?= $base64 ?>" />
d -->