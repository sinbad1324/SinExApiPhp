<?php
namespace ImageAPI;
use Jenssegers\ImageHash\{
    ImageHash,
    Hash
};
use Jenssegers\ImageHash\Implementations\DifferenceHash;
class Comparator{
   public static $Hasher;
   
   /**
    * This function init the hasher is  dont exsit
    */
   public static function init(){
    if (Comparator::$Hasher == null) Comparator::$Hasher =  new ImageHash(new DifferenceHash());
   }

   /**
    * this function will comparate 2 hash and give the commparate 1-100
    *  @param Hash 
    *  @param Hash
    * @return int
    */
   public static function CreateComparate(Hash $h1 ,Hash $h2) : int {
        $distance = Comparator::$Hasher->distance($h1,$h2);
        return 100 - ($distance / 64 * 100);
   }



}

?>