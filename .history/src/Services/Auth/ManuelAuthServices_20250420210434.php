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
        $CodePin= $this->CreateVerificationCode();
       if (UserModel::CreateManuelUser($this->UserData ,)) {
            //Envoyer un mail
       }
    }
    public function CreateVerificationCode() : string {
        return "";
    }

}

?>