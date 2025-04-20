<?php 
namespace Entities;


final class UserEntities{
    public array $userData;
    function __construct($userData)
    {
     $this->userData = $userData;   
    }

    public function GetId() : int {
        return $this->userData["userId"];
    }

}

?>