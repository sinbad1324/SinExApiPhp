<?php 
namespace src\Controllers;
use Psr\Container\ContainerInterface;
use Traits\DataFormatTrait;
//requires
require_once "../src/traits/DataFormatTrait.php";
class BaseController{
    use DataFormatTrait;
    protected $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}

?>