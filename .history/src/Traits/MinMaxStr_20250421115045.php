<?php 

trait MinMaxStr{
    public function ClampString(string $str, int $min, int $max):bool
    {
        $nameCount = strlen($str);
        if ($nameCount > 2 && $nameCount <= 50)
            return true;
        return false;
    }
}


?>