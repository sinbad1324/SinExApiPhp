<?php 
namespace Entities;

use Models\UserModel;
use Traits\StringShuffle;
use Services\JWT\JWTService;
require_once "../src/Services/JWT/JWTService.php";
require_once "../src/Models/UserModel.php";
require_once "../src/Traits/StringShuffle.php";

final class UserEntities{
    use StringShuffle;
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
        return password_verify($password , $this->userData["password"]);
    }
    public function MailPinCodeIsSame($code) : int {
        return  $this->userData["emailVerificationCode"] == $code;
    }
    //updates
    public function SetUserToChecked() : bool {
       return UserModel::UpdatePinCodeUser(null , $this->GetId()) && UserModel::UpdateCheckedUser(TRUE , $this->GetId());
    }
    public function RemakeNewCode() : string {
        if (!$this->userData["verifiedEmail"]) {
            $code = static::RandomString(random_int(0,20),6);
            UserModel::UpdatePinCodeUser( $code , $this->GetId());
            return $code;
        }
        return "";
    }
    public function IsChecked() : bool {
        return $this->userData["verifiedEmail"]?? false;
    }

    public function  CreateJWTforUser() : string {
        return JWTService::CreateNewJWT([
            "userName"=>$this->GetName(),
            "id"=>$this->GetId(),
            'iat' => time(), 
            'exp'=> (time()+604800) // une semaine
        ]);
    }
}

?>