<?php

namespace Services\Auth;

use Models\UserModel;
use Traits\DataFormatTrait;
use Traits\StringShuffle;

final class ManuelAuthServices
{
    use StringShuffle, DataFormatTrait;
    private array $UserData;
    public  function __construct($UserData)
    {
        $this->UserData = $UserData;
    }
    public function RegitreNewUser(): string
    {
        $CodePin = $this->CreateVerificationCode();
        if (UserModel::CreateManuelUser($this->UserData, $CodePin)) {
            //Envoyer un mail
            return $this->json(
                "Nous vous avons envoyer un mail a votre mail vouliez vous nous donner ce code.",
                ["code" => $CodePin, "id" => UserModel::GetLastUserAdded()->GetId()],
                true
            ); // atention c'est mode dbug ici il foudra enlever code de data 
        }
        return $this->json("Il y a eu un probléme lors de la création de votre compte!", []);
    }
    public function CreateVerificationCode(): string
    {
        return $this->RandomString(random_int(0, 20), 6);
    }

    public function VerifieTheCode($code, $id) {}
}
