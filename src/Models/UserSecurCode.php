<?php
namespace Models;

use Entities\UserEntities;
use Services\DB\DBConnection;


use PDO;
require_once "../src/Services/DB/DBConnection.php";

final class UserSecurCode{

    /**
     *Cette function est utiliser pour créé un nouveau utilisateur qui s'est inscrit manuelement sur le site
     * @param array $data
     */
    public static function CreateNewCode($userId ,$code)  {
            $conn = DBConnection::GetConnection("sinox");
            $sth=$conn->prepare("INSERT INTO userSecurCode(userId,code)VALUES(?,?)");
            $sth->bindParam(1 , $userId,PDO::PARAM_INT , 10);
            $sth->bindParam(2 , $code ,PDO::PARAM_STR , 255);
            return $sth->execute();
    }
    //Getters
    public static function GetLastCodeFromUser($userId)  {
        $conn = DBConnection::GetConnection("sinox");
        $sth = $conn->prepare("SELECT * FROM userSecurCode WHERE id = (SELECT MAX(id) FROM userSecurCode WHERE userId=?);");
        $sth->bindParam(1 , $userId,PDO::PARAM_INT , 10);
        if ($sth->execute()) 
            return $sth->fetch(PDO::FETCH_ASSOC);
     return null;   
    }
    //findes
    public static function FindWithUserId($userId) : array|null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM userSecurCode WHERE userId=?");
        $sth->bindParam(1 , $userId,PDO::PARAM_INT , 10);
        if ($sth->execute()) 
            return $sth->fetchAll(PDO::FETCH_ASSOC);
     return null;   
    }

    public static function FindWithUserIdAndId($userId , $codeId) : array|null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM userSecurCode WHERE userId=? AND id =?");
        $sth->bindParam(1 , $userId,PDO::PARAM_INT , 10);
        $sth->bindParam(2 , $codeId,PDO::PARAM_INT , 10);
        if ($sth->execute()) 
            return $sth->fetch(PDO::FETCH_ASSOC);
     return null;   
    }

    public static function FindWithUserIdAndCode($userId , $code) : array|null {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM userSecurCode WHERE userId=? AND code =?");
        $sth->bindParam(1 , $userId,PDO::PARAM_INT , 10);
        $sth->bindParam(2 , $code,PDO::PARAM_STR , 10);
        if ($sth->execute()) 
            return $sth->fetch(PDO::FETCH_ASSOC);
     return null;   
    }

    //Deletes
    public static function DeleteWithId($id) : bool {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("DELETE FROM userSecurCode WHERE id=?");
        $sth->bindParam(1 , $id,PDO::PARAM_INT , 10);
        return $sth->execute();
    }
}
?>