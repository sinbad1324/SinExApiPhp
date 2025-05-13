<?php

namespace Entities;

use Models\UserSecurCode;
use Traits\StringShuffle;
use Services\JWT\JWTService;

require_once "../src/Services/JWT/JWTService.php";
require_once "../src/Models/UserModel.php";
require_once "../src/Traits/StringShuffle.php";

final class UserCodeSecurEntites
{
    use StringShuffle;
    public array $data;
    function __construct($data)
    {
        $this->data = $data;
    }

    public function GetUserId(): int
    {
        return $this->data["userId"];
    }
    public function GetCode(): string
    {
        return $this->data["code"];
    }
    public function GetId(): int
    {
        return $this->data["id"];
    }
    public function GetdateCreation(): string
    {
        return $this->data["dateCreation"];
    }
    public function GetdateExpiration(): string
    {
        return $this->data["dateExpiration"];
    }
    public function GetdateFin(): string|null
    {
        return $this->data["dateFin"];
    }
    //Verification
    public function IsChecked(): string
    {
        return $this->data["checked"];
    }
    public function DateIsValide()
    {
        $EndDate = strtotime($this->GetdateExpiration());

        if (time() > $EndDate)
            return false;
        return true;
    }

    public function IsValideCode($code)
    {
        return $this->GetCode() == $code;
    }

    // update
    public  function SetChecked($value = true)
    {
        UserSecurCode::UpdateChecked($value, $this->GetId());
    }
    public function SetFinish($date=NULL)
    {
        if (!$date) {
            $date = new \DateTime('now');
            $date=$date->format("Y-m-d");
            echo $date;
        }

        UserSecurCode::UpdateDateFin($date, $this->GetId());
    }
    public function SetNewCode($code)
    {
        if (!$code) return;
        UserSecurCode::UpdateCodeWithId($code, $this->GetId());
    }
}
