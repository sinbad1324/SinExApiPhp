<?php 
namespace Entities;

use Models\UserModel;
use Traits\StringShuffle;
use Services\JWT\JWTService;
require_once "../src/Services/JWT/JWTService.php";
require_once "../src/Models/UserModel.php";
require_once "../src/Traits/StringShuffle.php";

final class UserCodeSecurEntites{
    use StringShuffle;
    public array $data;
    function __construct($data)
    {
      $this->data = $data;   
    }

    public function GetUserId() : int {
        return $this->data["userId"];
    }
    public function GetCode() : string {
        return $this->data["code"];
    }
    public function GetId() : int {
        return $this->data["id"];
    }
    
}

?>