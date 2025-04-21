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

    /**
     * Ce code verifie si le id est valide puis créé une initialze le utilisateur verifie que e code est juste puis le met en checked
     * @param string $code
     * @param string|int $id
     */
    public static function VerifieCode(string $code, string|int $id): string | null{
        $ud = UserModel::FindWithId($id);
        if ($ud) { // verifier que le utilisateur existe;
            $user = new UserEntities($ud); // initializer le utilisateur
            if ($user->MailPinCodeIsSame($code)){  // verifier si le code est la meme
                if (!$user->SetUserToChecked())   // on peut le utilisateur si veifié
                    return static::json("Nous avons pas réussit a vous verifier voulez vous essayer plus tard!", []); 
            }else // le code n'est pas la meme
                return static::json("Ce code est invalide!", []); 
        }else // le code ne existe pas!
           return static::json("Ce compte n'existe pas", []); 
        return null; // il n'y a pas eu de erreur tout ces passé bien :> xD
    }

    public static function RemakeVerificationCode( $id):string {
        $ud = UserModel::FindWithId($id);
        if ($ud) {
            $user = new UserEntities($ud);
            if (!$user->IsChecked()) 
                return static::json("Un nouveau code vous a été envoyer par mail!", ["code"=>$user->RemakeNewCode()]); // ne pas donne le code en deploiment mais envoyer un mail
            else 
                return static::json("Vous etes déja verifier!",[]);
        }
        else
           return static::json("Ce compte n'existe pas", []); 
    }
    /**
     * Valider le utilisateur
     * Et puis lui envoyer le jwt et ces donner non sensible!!!
     */
    public static function Connection(array $data) : string {
        $ud = UserModel::FindWithEmail($data["email"]);
        if ($ud) {
            $user = new UserEntities($ud);
            if (!$user->IsChecked()) 
                return static::json("Cette utilisateur n'est pas encore verifié!",[]);
            if ($user->PasswordIsSame($data["password"])) 
               return static::json("Vous etes connecté!" , ["JWT"=> $user->CreateJWTforUser(),"userData"=>["userName"=>$user->GetName(),"userId"=>$user->GetId()]] ,true);
        }
        return static::json("Cette utilisateur n'existe pas! Soit le mail est faux saoit le password est faux!!!",[]);
    }
}
