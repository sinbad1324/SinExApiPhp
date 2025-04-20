<?php

namespace Services\Auth;

use Entities\UserEntities;
use Models\UserModel;
use Traits\DataFormatTrait;
use Traits\StringShuffle;

require_once "../src/Traits/DataFormatTrait.php";
require_once "../src/Traits/StringShuffle.php";
require_once "../src/Models/UserModel.php";
final class ManuelAuthServices
{
    use StringShuffle, DataFormatTrait;
    // private array $UserData;
    // public  function __construct($UserData)
    // {
    //     $this->UserData = $UserData;
    // }
    public static function RegitreNewUser($UserData): string
    {
        $CodePin = static::CreateVerificationCode();
        if (UserModel::CreateManuelUser($UserData, $CodePin)) {
            //Envoyer un mail
            return static::json(
                "Nous vous avons envoyer un mail a votre mail vouliez vous nous donner ce code.",
                ["code" => $CodePin, "id" => UserModel::GetLastUserAdded()["userId"]],// atention c'est mode dbug ici il foudra enlever code de data 
                true
            ); 
        }
        return static::json("Il y a eu un probléme lors de la création de votre compte! Ou quel qu'un a déja un compte avec ce mail ou ce prénom.", []);
    }
    public static function CreateVerificationCode(): string
    {
        return static::RandomString(random_int(0, 20), 6);
    }

    public function VerifieTheCode($code, $id) {
        $ud = UserModel::FindWithId($id);
        if ($ud) {
            $user = new UserEntities($ud);
            if ($user->MailPinCodeIsSame($code)) {
                if (!$user->SetUserToChecked()) {
                    return static::json("Nous avons pas réussit a vous verifier voulez vous essayer plus tard", []); 
                }
            }
            else
                return static::json("Ce code est invalide!", []); 
        }
        else
           return static::json("Ce compte n'existe pas", []); 

    }
}
