<?php 
namespace Traits;

trait stringShuffle {

    function RandomString(int $startPos,int $length) : string {
        $randomletter = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTU",5)), 0, $length);

    }
}


?>