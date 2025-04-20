<?php

namespace Services\Auth;

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
        var_dump(UserModel::GetLastUserAdded());
        if (UserModel::CreateManuelUser($UserData, $CodePin)) {
            //Envoyer un mail
            return static::json(
                "Nous vous avons envoyer un mail a votre mail vouliez vous nous donner ce code.",
                ["code" => $CodePin, "id" => UserModel::GetLastUserAdded()["userId"]],// atention c'est mode dbug ici il foudra enlever code de data 
                true
            ); 
        }
        return static::json("Il y a eu un probléme lors de la création de votre compte!", []);
    }
    public static function CreateVerificationCode(): string
    {
        return static::RandomString(random_int(0, 20), 6);
    }

    public function VerifieTheCode($code, $id) {}
}
