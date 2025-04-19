<?php 
namespace src\Controllers;

class BaseController{
    public $content;
    function __construct($content)
    {
        $this->$content = $content;
    }
}

?>