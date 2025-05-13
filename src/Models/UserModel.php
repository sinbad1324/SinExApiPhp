<?php
namespace Models;

use Entities\UserEntities;
use Services\DB\DBConnection;


use PDO;
require_once "../src/Services/DB/DBConnection.php";
require_once "../src/Entities/UserEntities.php";

final class UserModel{

    /**
     *Cette function est utiliser pour créé un nouveau utilisateur qui s'est inscrit manuelement sur le site
     * @param array $data
     */
    public static function CreateManuelUser(array $data)  {

        if (!static::FindWithEmail($data["email"]) && !static::FindWithName($data["userName"])) {
            $conn = DBConnection::GetConnection("sinox");
            $sth=$conn->prepare("INSERT INTO user(userName,email,password,ruleAccepted,createdDate)VALUES(?,?,?,?,NOW())");
            $sth->bindParam(1 , $data["userName"] ,PDO::PARAM_STR , 50);
            $sth->bindParam(2 , $data["email"] ,PDO::PARAM_STR , 255);
            $hashedPass = password_hash($data["password"], PASSWORD_BCRYPT, ['cost' => 13]);
            $sth->bindParam(3 ,  $hashedPass ,PDO::PARAM_STR , 150);
            $sth->bindParam(4 , $data["ruleAccepted"]  ,PDO::PARAM_BOOL );
            return $sth->execute();
        }
        return null;
    }

    public static function CreateOAuthUser(array $data)  {

        if (!static::FindWithEmail($data["email"]) && !static::FindWithName($data["userName"])) {
            $conn = DBConnection::GetConnection("sinox");
            $sth=$conn->prepare("INSERT INTO user(userName,email,googleId,ruleAccepted,verifiedEmail ,createdDate)VALUES(?,?,?,TRUE,TRUE,NOW())"); // SANS accepter les régle on neu peut pas se inscrir
            $sth->bindParam(1 , $data["userName"] ,PDO::PARAM_STR , 50);
            $sth->bindParam(2 , $data["email"] ,PDO::PARAM_STR , 255);
            $sth->bindParam(3 ,  $data["googleId"] ,PDO::PARAM_STR , 150);
            return $sth->execute();
        }
        return null;
    }

    //Findes
    public static function FindWithId($id) : array | null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM user WHERE userId = ?;");
        $sth->bindParam(1 , $id ,PDO::PARAM_INT , 10);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
                if ($data && count($data)>1){
            return $data;
        }
        return null;
    }

    public static function FindWithGoogleId($googleId) : array | null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM user WHERE googleId = ?;");
        $sth->bindParam(1 , $googleId ,PDO::PARAM_INT , 10);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
                if ($data && count($data)>1){
            return $data;
        }
        return null;
    }

    public static function FindWithEmail($email) : array | null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM user WHERE email = ?;");
        $sth->bindParam(1 , $email ,PDO::PARAM_STR , 255);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
                if ($data && count($data)>1)
            return $data;
        return null;
    }
    public static function FindWithName($name) : array | null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM user WHERE userName = ?;");
        $sth->bindParam(1 , $name ,PDO::PARAM_STR , 50);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        if ($data && count($data)>1){
            return $data;
        }
        return null;
    }
    //getters
    public static function GetLastUserAdded():array{
        $conn = DBConnection::GetConnection("sinox");
        return $conn->query("SELECT * FROM user WHERE userId = (SELECT MAX(userId) FROM user);")->fetch(PDO::FETCH_ASSOC);
    }

    //updates
    public static function UpdateUserName(string $name , $id):bool{
        if ($name == "") return false;
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("UPDATE user SET userName=? WHERE userId =?;");
        $sth->bindParam(1 , $name ,PDO::PARAM_STR , 1);
        $sth->bindParam(2 , $id ,PDO::PARAM_INT , 10);
        return $sth->execute();
    }
    public static function UpdateUserEmail(string $email , $id):bool{
        if ($email == "") return false;
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("UPDATE user SET email=? WHERE userId =?;");
        $sth->bindParam(1 , $email ,PDO::PARAM_STR , 1);
        $sth->bindParam(2 , $id ,PDO::PARAM_INT , 10);
        return $sth->execute();
    }
    public static function UpdateUserPassword(string $password , $id):bool{
        if ($password == "") return false;
        $hashedPass = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("UPDATE user SET email=? WHERE userId =?;");
        $sth->bindParam(1 , $hashedPass ,PDO::PARAM_STR , 1);
        $sth->bindParam(2 , $id ,PDO::PARAM_INT , 10);
        return $sth->execute();
    }

}
?>