<?php
namespace Services\Auth;

use Models\UserModel;
use Traits\StringShuffle;

final class ManuelAuthServices {
    use StringShuffle;
    private array $UserData;
    public  function __construct($UserData)
    {
        $this->UserData = $UserData;
    }
    public function RegitreNewUser()  {
        $CodePin= $this->CreateVerificationCode();
       if (UserModel::CreateManuelUser($this->UserData ,$CodePin)) {
            //Envoyer un mail
       }
    }
    public function CreateVerificationCode() : string {
        return "";
    }

}

?>