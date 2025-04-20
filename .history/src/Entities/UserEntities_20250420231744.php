<?php 
namespace Entities;

use Models\UserModel;
require_once "../src/Models/UserModel.php";

final class UserEntities{
    public array $userData;
    function __construct($userData)
    {
     $this->userData = $userData;   
    }

    public function GetId() : int {
        echo $this->userData["userId"];
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
        return password_verify($password , $this->userData["password"]);
    }
    public function MailPinCodeIsSame($code) : int {
        return  $this->userData["emailVerificationCode"] == $code;
    }
    //updates
    public function SetUserToChecked() : bool {
       return UserModel::UpdatePinCodeUser("NULL" , $this->GetId()) && UserModel::UpdateCheckedUser(TRUE , $this->GetId());
    }
    public function RemakeNewCode() : bool {
        return UserModel::UpdatePinCodeUser("NULL" , $this->GetId());
     }
}

?>