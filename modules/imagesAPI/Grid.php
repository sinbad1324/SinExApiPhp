<?php
namespace ImageAPI;

define('MAX_ERROR', '1');
class Grid
{
    //private
    private $err2 = 0;
    private $err4 = 0;
    private $err8 = 0;
    private Img $image;
    //Public 
    /**
     * THis function it's a constructor for the class Grid and this class are goning to define if the img is a x,2x2 or 8x8xD
     * @param Img $image
     */
    public function __construct(Img $image)
    {
        $this->image = $image;
    }
    /**
     * Descripion: this function difine if a color rgba is black or ...
     * @param array $color the color
     * @return bool TRUE if is black and FALSE if is not black;
     */
    private function IsBlack(array $color): bool
    {
        if ($color['r'] == 0 && $color['g'] == 0 && $color['b'] == 0) return true;
        return false;
    }
    /**
     * Description: This function calculat if the image is 4x4,8x8 ,2x2, noraml
     * @param void
     * @return string Return the resulta en the algo
     */
    public function Start(): string
    {
        $flip = "normal";
        $size = $this->image->GetSize();
        $x2Start = ($size["y"] / 2);
        $x4Start = ($size["y"] / 4);
        $x8Start = ($size["y"] / 8) * 3;
        //Motor
        $img = $this->image->image;
        if (!$this->IsBlack($img->getImagePixelColor(0, 0)->getColor())) {
            for ($i = 0; $i < $img->getImageWidth(); $i++) {
                if ($this->IsBlack($img->getImagePixelColor($i, $x2Start)->getColor())) {$this->err2++;}
                if ($this->IsBlack($img->getImagePixelColor($i, $x4Start)->getColor())){ $this->err4++;}
                if ($this->IsBlack($img->getImagePixelColor($i, $x8Start)->getColor())){ $this->err8++;}
            }   
        }else{
            for ($i = 0; $i < $img->getImageWidth(); $i++) {
                if (!$this->IsBlack($img->getImagePixelColor($i, $x2Start)->getColor())) {$this->err2++;}
                if (!$this->IsBlack($img->getImagePixelColor($i, $x4Start)->getColor())){ $this->err4++;}
                if (!$this->IsBlack($img->getImagePixelColor($i, $x8Start)->getColor())){ $this->err8++;}
            } 
        }
        //DEbug
        $this->drawLine(0,$x4Start,$size['x'],$x4Start,"green");
        $this->drawLine(0,$x2Start,$size['x'],$x2Start,"blue");
        $this->drawLine(0,$x8Start,$size['x'],$x8Start,"red");

        //DEbug
        echo $this->err2."<br>";
        echo $this->err4."<br>";
        echo $this->err8."<br>";
        $bool2 = $this->err2 < MAX_ERROR; 
        $bool4 = $this->err4 < MAX_ERROR; 
        $bool8 = $this->err8 < MAX_ERROR; 
        if ($bool2 && $bool4 && ! $bool8)
            $flip = "4x4";
        elseif ($bool2 && ! $bool4 && ! $bool8)
            $flip = "2x2";
        elseif ($bool2 && $bool4 && $bool8)
            $flip = "8x8";
        return $flip;
    }
    private function drawLine($sx,$sy,$ex,$ey ,$color="black"){
        $draw = new \ImagickDraw();
        $draw->setStrokeColor(new \ImagickPixel( $color ));
        $draw->setFillColor(new \ImagickPixel( $color ));
        $draw->setStrokeWidth(2);
        $draw->setFontSize(72);
        $draw->line($sx,$sy,$ex,$ey);
        $this->image->image->drawImage($draw);
    }
}
