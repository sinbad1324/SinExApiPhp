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
    public function GetName() : int {
        return $this->userData["userName"];
    }
    public function GetEmail() : int {
        return $this->userData["email"];
    }
    public function GetId() : int {
        return $this->userData["userId"];
    }
}

?>