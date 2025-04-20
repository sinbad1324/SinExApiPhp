<?php 
namespace Entities;


final class UserEntities{
    public array $userData;
    function __construct($userData)
    {
     $this->userData = $userData;   
    }

    function GetId() : int {
        return $userData["userId"];
    }

}

?>