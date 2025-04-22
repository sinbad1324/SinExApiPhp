<?php 
namespace src\Controllers;
use Psr\Container\ContainerInterface;
use Traits\DataFormatTrait;
use Traits\MinMaxStr;
use Traits\StringShuffle;

//requires
require_once "../src/Traits/DataFormatTrait.php";
require_once "../src/Traits/MinMaxStr.php";

class BaseController{
    use DataFormatTrait,MinMaxStr;
    protected $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}

?>