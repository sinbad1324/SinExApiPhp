<?php
namespace ImageAPI;
use \Imagick;
use Intervention\Image\Image;
use \Jenssegers\ImageHash\Hash;
//inclues
include_once "./modules/imagesAPI/Grid.php";
include_once "./modules/imagesAPI/CompareImages.php";
//const
define("UNSCALER" , 15); // apres 15 c'es pas tres precis
 class Img{
    //Publics
    public  $image;
    public $assetId;
    public $bgColor;
    public $debug=true;
    private $hash;
    private $size;
    //Private
    /**
     * Desciption: this class is a images class take a image ou image path and init a ImagICK class is for treaty
     * @param mixed | ressource or a string filepath
     */
    public function __construct(mixed  $img ,string $assetId="")
    {
        if (is_resource($img)) {
            $this->image=new Imagick();
            $this->image->readImageFile($img);
        }elseif (gettype($img) == "string") {
            $this->image=new Imagick($img);
        }
        $this->assetId=$assetId;
        $this->Init();
    }
    /**
     * Description:init the class 
     * @return void
     */
    private function Init() {
        $this->BlackWhiteImg();
        $this->ResizeImages(UNSCALER);
    }

    /**
     * Description : THis function Biniraise he image the background white and images object black
     * @param void
     * @return void
     */
    public function BlackWhiteImg() : void {
        var_dump($this->image->getImagePixelColor(0, $this->GetSize()["y"])->getColor());
        if (Grid::IsWhite($this->image->getImagePixelColor(0, 0)->getColor())) {
            $this->image->thresholdImage(.95 * Imagick::getQuantumRange()['quantumRangeLong']); // Made by chatgpt // Meilleur valeur .55
            $this->bgColor=true;
        }else{
            $this->bgColor=false;
            $this->image->thresholdImage(.05 * Imagick::getQuantumRange()['quantumRangeLong']); // Made by chatgpt // Meilleur valeur .'05'
        }
    }
    /**
     * Description this function resize the image and give him a new size; Or Like unScla hime
     * @param int unScale
     * @param Imagick::FILTER_POINT $filter Defult is Imagick::FILTER_POINT ans is very speed
     * @return
     */
    public function ResizeImages(int $div ,$filter=Imagick::FILTER_POINT) :void{
        $size = $this->GetSize() ;
        $this->image->resizeImage($size["x"]/$div ,$size["y"]/$div ,$filter,1);
    }
    /**
     * Description: This functione give u the image modified or not; Like [x:0,y:0];
     */
    function GetSize() : array {
        if ($this->size == null) {
            $this->size =["x"=>$this->image->getImageWidth() , "y"=>$this->image->getImageHeight()];
        }
        return$this->size;
    }
    /**
     * This function return the Grid->start resutl -> (This function calculat if the image is 4x4,8x8 ,2x2, noraml)
     */
    public function GetFlipbookIs($h=true) : string {
        $grd =  new Grid($this);
        if ($h) {
            return $grd->StartH();
        }
        return $grd->Start();
    }
    /**
     * Description: This methodes show  the image in the html web
     * Transform the image to binaire object adn encode is to base64 and return an image 
     * @param void
     * @return void
     */
    public function Show(){
        if (!$this->debug)return;
        $imageBlob = $this->image->getImageBlob();
        $base64 = base64_encode($imageBlob);
        echo "<br><img src='data:image/png;base64,$base64' /><br>";
    }

    /**
     * Dscrtipion : this methodes return the final result the categoris with flip and size;
     * @param array &$data give the charged assets
     * @return array ["size"=>[x=>float , y=>float] , "Flipbook"=>string , "categorie"=>string];
     */
    public function GetResult(&$data) : array {
        $avg = [];
        $flip = $this->GetFlipbookIs();
        foreach ($data[$flip] as $key => $value) {
            $somme=0;
            foreach ($value as $H) {
                $somme += Comparator::CreateComparate($this->GetHash() , $H);
            }
            $avg[$key] = $somme/count($value);
        }
        asort($avg);
        return ["size"=>$this->GetSize() , "Flipbook"=>$flip , "categorie"=>array_keys($avg)[0]];
    }
    
    /**
     * 
     * the function hash a image with color and pixels
     * @return Hash
     */
    public function GetHash():Hash{
        if ($this->hash == null) {
            $this->hash= Comparator::$Hasher->hash($this->image);
        }
        return  $this->hash;
    }
}

?>