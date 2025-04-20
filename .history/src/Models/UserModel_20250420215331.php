<?php
namespace Models;
use Services\DB\DBConnection;
use PDO;
final class UserModel{

    /**
     *Cette function est utiliser pour créé un nouveau utilisateur qui s'est inscrit manuelement sur le site
     * @param array $data
     */
    public static function CreateManuelUser(array $data,string $CodePin)  {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("INSERT INTO user(userName,email,password,emailVerificationCode,createdTime)VALUES(?,?,?,?,'NOW()')");
        $sth->bindParam(1 , $data["userName"] ,PDO::PARAM_STR , 50);
        $sth->bindParam(2 , $data["email"] ,PDO::PARAM_STR , 255);
        $sth->bindParam(3 , password_hash($data["password"], PASSWORD_BCRYPT, ['cost' => 13])  ,PDO::PARAM_STR , 150);
        $sth->bindParam(4 , $CodePin  ,PDO::PARAM_STR , 7);
        return $sth->execute();
    }


    //Findes
    public static function FindWithId($id) : array {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("SELECT * FROM user WHERE userId == ?;");
        $sth->bindParam(1 , $id ,PDO::PARAM_INT , 50);

    }

    //getters
    public static function GetLastUserAdded():array{
        $conn = DBConnection::GetConnection("sinox");
        return $conn->query("SELECT * FROM user WHERE userId == MAX(userId);")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>