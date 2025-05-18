<?php

namespace Services\Auth;

use Models\UserSecurCode;
use Models\UserModel;
use Traits\DataFormatTrait;
use Traits\StringShuffle;
use Entities\UserCodeSecurEntites;
use Mailer\Mailer;

require_once "../src/Traits/DataFormatTrait.php";
require_once "../src/Traits/StringShuffle.php";
require_once "../src/Models/UserSecurCode.php";
require_once "../src/Models/UserModel.php";
require_once "../src/Entities/UserCodeSecurEntites.php";
require_once "../src/Services/mail/Mailer.php";

class UpdatePasswordService
{
    use DataFormatTrait, StringShuffle;
    public static function GenetateNewCode($userid)
    {
        $userModel = UserModel::FindWithId($userid);
        if (!$userModel) {
            return static::json("Cette utilisateur ne existe pas!");
        }
        $code = static::RandomString(6, 10);
        if (UserSecurCode::CreateNewCode($userid, $code, 1, "PasswordChange")) {
            $newcodeEnt = new UserCodeSecurEntites(UserSecurCode::GetLastCodeFromUser($userid));
            Mailer::Send($userModel["email"], $userModel["userName"], "ChangePasswordCode", "url for change your password : http://localhost:5173/user/change-password/?code=$code&id=" . $newcodeEnt->GetId());
            return static::json("nous vous avons evoyer un mail pour changer votre password!", [], true); // elever les info ici c en mode debug
        }
    }

    public static function VerifieCode($userid, $code, $newPassword)
    {
        $data = UserSecurCode::FindWithUserIdAndCode($userid, $code);
        if ($data) {
            $codeEnt = new UserCodeSecurEntites($data);
            if ($codeEnt->IsChecked())
                return static::json("Ce code est deja utilisé!");
            if (!UserModel::UpdateUserPassword($newPassword, $userid))
                return static::json("Nous avons pas pu change de password!");
            // UserSecurCode::DeleteWithId($data["id"]);
            $codeEnt->SetChecked(true);
            $codeEnt = null;
            return   static::json("Nous avons changer votre password!", [], true);
        }
    }
}
