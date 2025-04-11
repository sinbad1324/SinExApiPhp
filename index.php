<?php

require "./vendor/autoload.php";

use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

$div = 20;
define('MAX_ERROR', '1');


$img= new Imagick('images/LeafSpec4x4.png');
$img->setImageBackgroundColor('black');
$img = $img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
// $img->setImageColorspace(Imagrick::COLORSPACE_GRAY);
$img->thresholdImage(.01 * Imagick::getQuantumRange()['quantumRangeLong']); 
// $img->setImageFormat("jpg");
$img->resizeImage($img->getImageWidth()/$div ,$img->getImageHeight()/$div ,Imagick::FILTER_POINT,1);
echo ($img->getImageWidth()."x".$img->getImageHeight()/2)."<br>";
function IsBlack(array $color) : bool {
    if ($color['r']==0 && $color['g']==0 && $color['b']==0) return true;
    return false;
}

$err2 =0;
$err4 =0;
$err8 =0;

$x2Start = $img->getImageHeight()/2;
$x4Start = $img->getImageHeight()/4;
$x8Start = ($img->getImageHeight()/8)*3; 

for ($i=0; $i <$img->getImageWidth() ; $i++) { 
    if (!IsBlack($img->getImagePixelColor($i,$x2Start)->getColor())) $err2++;
    if (!IsBlack($img->getImagePixelColor($i,$x4Start)->getColor())) $err4++;
    if (!IsBlack($img->getImagePixelColor($i,$x8Start)->getColor())) $err8++;
}
echo "$err2<br>";
echo "$err4<br>";
echo "$err8<br>";

$bool2 = $err2 < MAX_ERROR;
$bool4 = $err4 < MAX_ERROR;
$bool8 = $err8 < MAX_ERROR;
if ($bool2 && $bool4 && ! $bool8)
    echo "4x4";
elseif ($bool2 && ! $bool4 && ! $bool8)
echo "2x2";
elseif  ($bool2 && $bool4 && $bool8)
echo "8x8";
else echo "Normal";

// if ($err2 <1) {
//     echo "2x2 <br>";
// }
// $img2 = clone $img;
// $img2->cropImage($img2 ->getImageWidth()/$div,$img->getImageHeight()/$div,0,0);
// $img3 = clone $img;
// $img3->cropImage($img3 ->getImageWidth()/$div,$img->getImageHeight()/$div,$img3 ->getImageWidth()/$div,0);

// $img4 = clone $img;
// $img4->cropImage($img4 ->getImageWidth()/$div,$img->getImageHeight()/$div,$img ->getImageWidth()/$div,$img ->getImageHeight()/$div);
// $img5 = clone $img;
// $img5->cropImage($img5 ->getImageWidth()/$div,$img->getImageHeight()/$div,0,$img ->getImageHeight()/$div);

// $hasher = new ImageHash(new DifferenceHash());

// $hash1 = $hasher->hash($img2);
// $hash2 = $hasher->hash($img3);

// $hash3 = $hasher->hash($img4);
// $hash4 = $hasher->hash($img5);

// // echo $hash1->toBits();
// echo($hasher->distance($hash1 , $hash2));
// echo("<br>");
// echo($hasher->distance($hash3 , $hash4));
// echo("<br>");
// echo($hasher->distance($hash3 , $hash2));
// echo("<br>");
// echo($hasher->distance($hash4 , $hash2));


// var_dump($hash1->distance($hash2));
// echo $img->getImageWidth()."x".$img->getImageHeight()."<br>";
// for ($i=0; $i < 200 ; $i++) { 
//     $pixel = $img->getImagePixelColor($i,192);
//     print_r($pixel->getColor());
//     $data[$i]=$pixel->getColor();
// }
// var_dump( $data);
$base64 = base64_encode($img);
// $base642 = base64_encode($img3);
// $base643 = base64_encode($img4);
// $base644 = base64_encode($img5);

?>
<img src="data:image/jpg;base64,<?= $base64 ?>" /><br>
<!-- <img src="data:image/jpg;base64,<?= $base642 ?>" /><br>
<img src="data:image/jpg;base64,<?= $base643 ?>" /><br>
<img src="data:image/jpg;base64,<?= $base644 ?>" /><br> -->
