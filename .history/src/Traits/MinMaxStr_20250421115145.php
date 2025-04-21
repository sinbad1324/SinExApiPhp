<?php 
namespace Traits;
trait MinMaxStr{
       /**
     * Cette function mesure la taille dun string
     * @param string $str data
     * @param int $min minimun size
     * @param int $max max size;
     */
    public function ClampString(string $str, int $min, int $max):bool
    {
        $nameCount = strlen($str);
        if ($nameCount > 2 && $nameCount <= 50)
            return true;
        return false;
    }
}


?>