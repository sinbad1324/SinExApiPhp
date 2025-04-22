<?php 
namespace Services\Auth;

use Models\UserSecurCode;
use Models\UserModel;
use Traits\DataFormatTrait;
use Traits\StringShuffle;
use Entities\UserCodeSecurEntites;
require_once "../src/Traits/DataFormatTrait.php";
require_once "../src/Traits/StringShuffle.php";
require_once "../src/Models/UserSecurCode.php";
require_once "../src/Models/UserModel.php";
require_once "../src/Entities/UserCodeSecurEntites.php";

class UpdatePasswordService {
    use DataFormatTrait,StringShuffle;
    public static function GenetateNewCode($userid){
        if (!UserModel::FindWithId($userid)) {
            return static::json("Cette utilisateur ne existe pas!");
        }
        $code = static::RandomString(6,10);
        if (UserSecurCode::CreateNewCode($userid , $code)) {
            $newcodeEnt = new UserCodeSecurEntites(UserSecurCode::GetLastCodeFromUser($userid));
            return static::json("nous vous avons evoyer un mail pour changer votre password!",["code"=>$code,"id"=>$newcodeEnt->GetId()],true); // elever les info ici c en mode debug
        }
    }

    public static function VerifieCode($userid,$code,$newPassword){
        $data = UserSecurCode::FindWithUserIdAndCode($userid,$code);
        if ($data) {
            if (!UserModel::UpdateUserPassword($newPassword,$userid)) 
                return static::json("Nous avons pas pu change de password!");
            UserSecurCode::DeleteWithId($data["id"]);
            return   static::json("Nous avons changer votre password!",[],true);
        }
    }
}


?>