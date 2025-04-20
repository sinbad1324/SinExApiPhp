<?php
namespace Services\Auth;

use Models\UserModel;

final class ManuelAuthServices {

    private array $UserData;
    public  function __construct($UserData)
    {
        $this->UserData = $UserData;
    }
    public function RegitreNewUser()  {
        UserModel::CreateManuelUser($UserData);
    }
    public function CreateVerificationCode() : string {
        return "";
    }

}

?>