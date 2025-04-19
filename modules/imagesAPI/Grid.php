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
    public static function IsBlack(array $color): bool
    {
        if ($color['r'] == 0 && $color['g'] == 0 && $color['b'] == 0 ) return true;
        return false;
    }
    public static function IsWhite(array $color): bool
    {
        if ($color['r'] == 255 && $color['g'] ==255 && $color['b'] == 255 &&$color['a'] != 0 ) return true;
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
        // var_dump($this->image->bgColor);
        if ($this->image->bgColor == true) { // bg white
            for ($i = 0; $i < $size["x"]; $i++) {
             if ($this->image->debug) {
                var_dump( $img->getImagePixelColor($i,$x2Start)->getColor())."2x2 white <br>";
                var_dump( $img->getImagePixelColor($i,$x4Start)->getColor())."4x4 white <br>";
                var_dump( $img->getImagePixelColor($i,$x8Start)->getColor())."8x8 white <br>";

             }
                if ($this->IsBlack($img->getImagePixelColor($i,$x2Start)->getColor())) {$this->err2++;}
                if ($this->IsBlack($img->getImagePixelColor($i,$x4Start)->getColor())){ $this->err4++;}
                if ($this->IsBlack($img->getImagePixelColor($i,$x8Start)->getColor())){ $this->err8++;}
            
            }   
        }else{ //bg black
            for ($i = 0; $i < $size["x"]; $i++) {
                if ($this->image->debug) {
                    var_dump( $img->getImagePixelColor($i,$x2Start)->getColor())."2x2 black <br>";
                    var_dump( $img->getImagePixelColor($i,$x4Start)->getColor())."4x4 black <br>";  
                    var_dump( $img->getImagePixelColor($i,$x8Start)->getColor())."8x8 black <br>";
                }
                if (!$this->IsBlack($img->getImagePixelColor($i,$x2Start)->getColor())) {$this->err2++;}
                if (!$this->IsBlack($img->getImagePixelColor($i,$x4Start)->getColor())){ $this->err4++;}
                if (!$this->IsBlack($img->getImagePixelColor($i,$x8Start)->getColor())){ $this->err8++;}
            } 
        }
        //DEbug
        if ($this->image->debug) {
            
            // $this->drawLine($x4Start,0,$x4Start,$size['y'],"green");
            // $this->drawLine($x2Start,0,$x2Start,$size['y'],"blue");
            // $this->drawLine($x8Start,0,$x8Start,$size['y'],"red");

            $this->drawLine(0,$x4Start,$size['y'],$x4Start,"green");
            $this->drawLine(0,$x2Start,$size['y'],$x2Start,"blue");
            $this->drawLine(0,$x8Start,$size['y'],$x8Start,"red");
            echo $this->err2."<br>";
            echo $this->err4."<br>";
            echo $this->err8."<br>";
        }
        //DEbug
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


    public function StartH(): string
    {
        $flip = "normal";
        $size = $this->image->GetSize();
        $x2Start = ($size["x"] / 2);
        $x4Start = ($size["x"] / 4);
        $x8Start = ($size["x"] / 8) * 3;
        //Motor
        $img = $this->image->image;
        if ($this->image->bgColor == true) { // bg white
            if ($this->image->debug) {
            echo "<h1>WHITE</h1>";
            }
            for ($i = 0; $i < $size["x"]; $i++) {
                if ($this->image->debug) {
                    var_dump( $img->getImagePixelColor($i,$x2Start)->getColor())."2x2 white <br>";
                    var_dump( $img->getImagePixelColor($i,$x4Start)->getColor())."4x4 white <br>";
                    var_dump( $img->getImagePixelColor($i,$x8Start)->getColor())."8x8 white <br>";
    
                 }
                if ($this->IsBlack($img->getImagePixelColor($x2Start,$i)->getColor())) {$this->err2++;}
                if ($this->IsBlack($img->getImagePixelColor($x4Start,$i)->getColor())){ $this->err4++;}
                if ($this->IsBlack($img->getImagePixelColor($x8Start,$i)->getColor())){ $this->err8++;}
            }   
        }else{ //bg black
            if ($this->image->debug) {
                echo "<h1>Black</h1>";
                }
            for ($i = 0; $i < $size["x"]; $i++) {
                if ($this->image->debug) {
                    var_dump( $img->getImagePixelColor($i,$x2Start)->getColor())."2x2 black <br>";
                    var_dump( $img->getImagePixelColor($i,$x4Start)->getColor())."4x4 black <br>";  
                    var_dump( $img->getImagePixelColor($i,$x8Start)->getColor())."8x8 black <br>";
                }          
                      
                if (!$this->IsBlack($img->getImagePixelColor($x2Start,$i)->getColor())) {$this->err2++;}
                if (!$this->IsBlack($img->getImagePixelColor($x4Start,$i)->getColor())){ $this->err4++;}
                if (!$this->IsBlack($img->getImagePixelColor($x8Start,$i)->getColor())){ $this->err8++;}
            } 
        }
        //DEbug
        if ($this->image->debug) {
            
            $this->drawLine($x4Start,0,$x4Start,$size['y'],"green");
            $this->drawLine($x2Start,0,$x2Start,$size['y'],"blue");
            $this->drawLine($x8Start,0,$x8Start,$size['y'],"red");
            echo "<br>".$this->err2."->2x2 blue <br>";
            echo $this->err4."->4x4 green <br>";
            echo $this->err8."->8x8 red <br>";
        }
        //DEbug
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

    /**
     * 
     * Draw A LINE into images
     */
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
