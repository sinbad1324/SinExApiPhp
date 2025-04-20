<?php
namespace Services\Auth;

final class ManuelAuthServices {

    private array $UserData;
    public  function __construct($UserData)
    {
        $this->UserData = $UserData;
    }
    public function RegitreNewUser()  {
        UserModel::CreateManuelUser()
    }
    public function CreateVerificationCode() : string {
        return "";
    }

}

?>