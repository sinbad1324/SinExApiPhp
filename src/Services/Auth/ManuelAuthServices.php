<?php

namespace Services\Auth;

use Entities\UserEntities;
use Mailer\Mailer;
use Models\UserModel;
use Traits\DataFormatTrait;
use Traits\StringShuffle;
use Models\UserSecurCode;

require_once "../src/Traits/DataFormatTrait.php";
require_once "../src/Traits/StringShuffle.php";
require_once "../src/Models/UserModel.php";
require_once "../src/Services/mail/Mailer.php";
require_once "../src/Entities/UserCodeSecurEntites.php";
require_once "../src/Models/UserSecurCode.php";

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
        $codePin = static::CreateVerificationCode();
        if (UserModel::CreateManuelUser($UserData)) {
            //Envoyer un mail
            $userId = UserModel::GetLastUserAdded()["userId"];
            UserSecurCode::CreateNewCode($userId, $codePin, 2, "EmailV");
            $mail = Mailer::Send($UserData["email"], $UserData["userName"], "Mail verirification code", "http://localhost:8080/api/auth/verifie-code?code=$codePin&id=$userId");
            if ($mail) {
                return static::json(
                    "Nous vous avons envoyer un mail a votre mail vouliez vous nous donner ce code.",
                    ["id" => $userId], // atention c'est mode dbug ici il foudra enlever code de data 
                    true
                );
            }
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
    public static function VerifieCode(string $code, string|int $id): string | null
    {
        $ud = UserModel::FindWithId($id);
        if ($ud) { // verifier que le utilisateur existe;
            $user = new UserEntities($ud); // initializer le utilisateur
            if ($user->IsChecked()) {
                return static::json("Vous etes deja verifié", []);
            }
            if (!$user->IsValideDateMail()) {
                return static::json("Votre code est expiré!", []);
            }
            if ($user->MailPinCodeIsSame($code)) {  // verifier si le code est la meme
                if (!$user->SetUserToChecked())   // on peut le utilisateur si veifié
                    return static::json("Nous avons pas réussit a vous verifier voulez vous essayer plus tard!", []);
            } else // le code n'est pas la meme
                return static::json("Ce code est invalide!", []);
        } else // le code ne existe pas!
            return static::json("Ce compte n'existe pas", []);
        return null; // il n'y a pas eu de erreur tout ces passé bien :> xD
    }

    public static function RemakeVerificationCode($id): string
    {
        $ud = UserModel::FindWithId($id);
        if ($ud) {
            $user = new UserEntities($ud);
            if (!$user->IsChecked()) {
                $codePin = $user->RemakeNewCode();
                if ($codePin != "") {
                    Mailer::Send($user->GetEmail(), $user->GetName(), "New mail verirification code", "http://localhost:8080/api/auth/verifie-code?code=$codePin&id=$id");
                }
                return static::json("Un nouveau code vous a été envoyer par mail!", [], true); // ne pas donne le code en deploiment mais envoyer un mail
            } else

                return static::json("Vous etes déja verifier!" . $user->GetName(), []);
        } else
            return static::json("Ce compte n'existe pas", []);
    }
    /**
     * Valider le utilisateur
     * Et puis lui envoyer le jwt et ces donner non sensible!!!
     */
    public static function Connection(array $data): string
    {
        $ud = UserModel::FindWithEmail($data["email"]);
        if ($ud) {
            $user = new UserEntities($ud);
            if (!$user->IsChecked())
                return static::json("Cette utilisateur n'est pas encore verifié!", []);
            if ($user->PasswordIsSame($data["password"])) {
                $codePin =static::RandomString(20,6);
                UserSecurCode::CreateNewCode($user->GetId(), $codePin , 1 , "DoubleAuth");
                if (Mailer::Send($user->GetEmail() , $user->GetName() , "Double auh verification" ,"http://localhost:5173/auth/double-auth-verification?code=$codePin&id=".$user->GetId() )) {
                    return static::json("Un mail vous a été envoyer!!!", [],true);
                }
            }
        }
        return static::json("Cette utilisateur n'existe pas! Soit le mail est faux saoit le password est faux!!!", []);
    }

    public static function VerifieDoubleAuth(array $data): string
    {

        $ud = UserModel::FindWithId($data["id"]);
        if ($ud) {
            $user = new UserEntities($ud);
            $code = UserSecurCode::FindWithUserIdAndCode($data["id"],$data["code"]);
            if ($code) 
                return static::json("Vous etes connecté!", ["JWT" => $user->CreateJWTforUser(), "userData" => ["userName" => $user->GetName(), "id" => $user->GetId()]], true);
        }
        return static::json("Cete code est faux!OU CETTE USER N'EXISTE PAS!", []);
    }
}
