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
    public static function CreateManuelUser(array $data,string $CodePin)  {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("INSERT INTO user(userName,email,password,emailVerificationCode,ruleAccepted,createdDate)VALUES(?,?,?,?,?,NOW())");
        $sth->bindParam(1 , $data["userName"] ,PDO::PARAM_STR , 50);
        $sth->bindParam(2 , $data["email"] ,PDO::PARAM_STR , 255);
        $hashedPass = password_hash($data["password"], PASSWORD_BCRYPT, ['cost' => 13]);
        $sth->bindParam(3 ,  $hashedPass ,PDO::PARAM_STR , 150);
        $sth->bindParam(4 , $CodePin  ,PDO::PARAM_STR , 7);
        $sth->bindParam(5 , $data["ruleAccepted"]  ,PDO::PARAM_BOOL );

        return $sth->execute();
    }


    //Findes
    public static function FindWithId($id) : UserEntities | null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM user WHERE userId == ?;");
        $sth->bindParam(1 , $id ,PDO::PARAM_INT , 10);
        $data = $sth->fetchAll();
        if ($data && count($data)>1){
            return new UserEntities($data);
        }
        return null;
    }

    public static function FindWithEmail($id) : UserEntities | null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM user WHERE email == ?;");
        $sth->bindParam(1 , $id ,PDO::PARAM_INT , 10);
        $data = $sth->fetchAll();
        if ($data && count($data)>1){
            return new UserEntities($data);
        }
        return null;
    }
    //getters
    public static function GetLastUserAdded():UserEntities{
        $conn = DBConnection::GetConnection("sinox");
        return new UserEntities($conn->query("SELECT * FROM user WHERE userId = (SELECT MAX(userId) FROM user);")->fetchAll(PDO::FETCH_ASSOC));
    }
}
?>