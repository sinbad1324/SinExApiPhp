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
    public function GetName() : string {
        return $this->userData["userName"];
    }
    public function GetEmail() : string {
        return $this->userData["email"];
    }
    public function GetGoogleId() : int {
        return $this->userData["googleId"];
    }

    //Verification
    public function PasswordIsSame($password) : int {
        return $this->userData["googleId"];
    }
}

?>