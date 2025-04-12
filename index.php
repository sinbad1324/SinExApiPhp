<?php
require "./vendor/autoload.php";
use ImageAPI\{
    Img,
    GetImageFromRoblox,
};

use function ImageAPI\GetImageFromRoblox;

include_once "./modules/imagesAPI/img.php";
include_once "./modules/imagesAPI/getImageFromRoblox.php";

GetImageFromRoblox("12237413962");
// $img = new Img("./images/4.png");
// echo $img->GetFlipbookIs()."<br>";

// $img2 = new Img("./images/1.jpg");
// echo $img2->GetFlipbookIs()."<br>";
// // $img->Show();
// // $img->Show();
// $base64 = base64_encode($img->image);
// $base642 = base64_encode($img2->image);

// 12237413962

// 85403020583447
// 16877946118
// 16981055058
// 16322490180 none
// 16879797476 none
// 13829407968 none
// 109440284900004 4y4

?>

<img src="data:image/jpg;base64,<?= $base64 ?>" /><br>
<img src="data:image/jpg;base64,<?= $base642 ?>" /><br>
