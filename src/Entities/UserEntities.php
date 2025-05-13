<?php

namespace Entities;

use Models\UserModel;
use Models\UserSecurCode;
use Traits\StringShuffle;
use Services\JWT\JWTService;

require_once "../src/Services/JWT/JWTService.php";
require_once "../src/Models/UserModel.php";
require_once "../src/Traits/StringShuffle.php";

final class UserEntities
{
    use StringShuffle;
    public array $userData;
    function __construct($userData)
    {
        $this->userData = $userData;
    }

    public function GetId(): int
    {
        return $this->userData["userId"];
    }
    public function GetName(): string
    {
        return $this->userData["userName"];
    }
    public function GetEmail(): string
    {
        return $this->userData["email"];
    }
    public function GetGoogleId(): int
    {
        return $this->userData["googleId"];
    }
    private function FinEmailVCode() : UserCodeSecurEntites|null {
        $SecureCodeModel = UserSecurCode::FindWithUserIdAndRaison($this->GetId(), "EmailV");
        if (!$SecureCodeModel) return null;
        return new UserCodeSecurEntites($SecureCodeModel);
    }
    //Verification
    public function PasswordIsSame($password): int
    {
        return password_verify($password, $this->userData["password"]);
    }
    public function MailPinCodeIsSame($code): bool
    {
        $EmailV = $this->FinEmailVCode();
        if (!$EmailV) return false;
        return  $EmailV->IsValideCode($code);
    }
    //updates
    public function SetUserToChecked(): bool
    {
        $EmailV = $this->FinEmailVCode();
        if (!$EmailV) return false;
        $EmailV->SetChecked(true);
        $EmailV->SetFinish();
        return true;
    }
    public function RemakeNewCode(): string|null
    {
        $EmailV = $this->FinEmailVCode();
        if (!$EmailV) return null;
        if (!$EmailV->IsChecked()) {
            $code = static::RandomString(random_int(0, 20), 6);
            $EmailV->SetNewCode($code);
            return $code;
        }
        return "";
    }
    public function IsChecked(): bool
    {
        $EmailV = $this->FinEmailVCode();
        if (!$EmailV) return false;
        return $EmailV->IsChecked();
    }
    public function IsValideDateMail(): bool
    {
        $EmailV = $this->FinEmailVCode();
        if (!$EmailV) return false;
        return $EmailV->DateIsValide();
    }

    public function  CreateJWTforUser(): string
    {
        return JWTService::CreateNewJWT([
            "userName" => $this->GetName(),
            "id" => $this->GetId(),
            'iat' => time(),
            'exp' => (time() + 604800) // une semaine
        ]);
    }
}
