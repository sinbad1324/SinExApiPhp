<?php 
namespace Traits;

trait StringShuffle {

    public static function RandomString(int $startPos,int $length) : string {
        return substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTU",5)), $startPos, $length);
    }
}


?>