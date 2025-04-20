<?php


final class ManuelAuthServices {

    private array $UserData;
    public  function __construct($UserData)
    {
        $this->UserData = $UserData;
    }
    public function RegitreNewUser()  {
        
    }
    public function CreateVerificationCode() : string {
        return "";
    }

}

?>