<?php

$img= new Imagick('images/lum.avif');
$img->setImageBackgroundColor('black');
$img = $img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
$img->setImageColorspace(Imagick::COLORSPACE_GRAY);
$img->thresholdImage(.05 * Imagick::getQuantumRange()['quantumRangeLong']); 

echo $img->getImageWidth()."x".$img->getImageHeight()."<br>";
$pixel = $img->getImagePixelColor(2/2,192);
print_r($pixel->getColor());
$base64 = base64_encode($img);

?>
<img src="data:image/jpg;base64,<?= $base64 ?>" />
d