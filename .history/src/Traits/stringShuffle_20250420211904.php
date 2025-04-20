<?php 
namespace Traits;

trait stringShuffle {

    function RandomString(int $length) : string {
        $randomletter = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTU"), 0, $length);

    }
}


?>