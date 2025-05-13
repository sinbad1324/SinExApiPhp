<?php

namespace Models;

use Services\DB\DBConnection;


use PDO;

require_once "../src/Services/DB/DBConnection.php";

final class UserSecurCode
{

    /**
     *Cette function est utiliser pour créé un nouveau utilisateur qui s'est inscrit manuelement sur le site
     * @param array $data
     */
    public static function CreateNewCode($userId, $code, $expirationDay , $raison)
    {
        
        $date = new \DateTime('now');
        $date->add(new \DateInterval("P".$expirationDay."D"));
        $conn = DBConnection::GetConnection("sinox");
        $sth = $conn->prepare("INSERT INTO userSecurCode(userId,code , raison , dateExpiration )VALUES(?,?,? ,? )");
        $sth->bindParam(1, $userId, PDO::PARAM_INT, 10);
        $sth->bindParam(2, $code, PDO::PARAM_STR, 255);
        $sth->bindParam(3, $raison, PDO::PARAM_STR, 50);
        $sth->bindParam(4, $date->format('Y-m-d'), PDO::PARAM_STR, 50);
        return $sth->execute();
    }
    //Getters
    /**
     * on cherche le derinier le dernier code envoyer a un user
     */
    public static function GetLastCodeFromUser($userId)
    {
        $conn = DBConnection::GetConnection("sinox");
        $sth = $conn->prepare("SELECT * FROM userSecurCode WHERE id = (SELECT MAX(id) FROM userSecurCode WHERE userId=?);");
        $sth->bindParam(1, $userId, PDO::PARAM_INT, 10);
        if ($sth->execute())
            return $sth->fetch(PDO::FETCH_ASSOC);
        return null;
    }
    //findes
    
    public static function FindWithUserId($userId): array|null
    {
        $conn = DBConnection::GetConnection("sinox");
        $sth = $conn->prepare("SELECT * FROM userSecurCode WHERE userId=?");
        $sth->bindParam(1, $userId, PDO::PARAM_INT, 10);
        if ($sth->execute())
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        return null;
    }

    public static function FindWithUserIdAndId($userId, $codeId): array|null
    {
        $conn = DBConnection::GetConnection("sinox");
        $sth = $conn->prepare("SELECT * FROM userSecurCode WHERE userId=? AND id =?");
        $sth->bindParam(1, $userId, PDO::PARAM_INT, 10);
        $sth->bindParam(2, $codeId, PDO::PARAM_INT, 10);
        if ($sth->execute())
            return $sth->fetch(PDO::FETCH_ASSOC);
        return null;
    }

    public static function FindWithUserIdAndCode($userId, $code): array|null
    {
        $conn = DBConnection::GetConnection("sinox");
        $sth = $conn->prepare("SELECT * FROM userSecurCode WHERE userId=? AND code =?");
        $sth->bindParam(1, $userId, PDO::PARAM_INT, 10);
        $sth->bindParam(2, $code, PDO::PARAM_STR, 10);
        if ($sth->execute())
            return $sth->fetch(PDO::FETCH_ASSOC);
        return null;
    }

    public static function FindWithUserIdAndRaison($userId, $raison): array|null
    {
        $conn = DBConnection::GetConnection("sinox");
        $sth = $conn->prepare("SELECT * FROM userSecurCode WHERE userId=? AND raison =?");
        $sth->bindParam(1, $userId, PDO::PARAM_INT, 10);
        $sth->bindParam(2, $raison, PDO::PARAM_STR, 10);
        if ($sth->execute())
            return $sth->fetch(PDO::FETCH_ASSOC);
        return null;
    }

    //update
    public static function UpdateChecked(bool $value , $id):bool{
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("UPDATE userSecurCode SET checked=? WHERE id =?;");
        $sth->bindParam(1 , $value ,PDO::PARAM_BOOL );
        $sth->bindParam(2 , $id ,PDO::PARAM_INT , 10);
        return $sth->execute();
    }
    public static function UpdateDateFin(string $value , $id):bool{
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("UPDATE userSecurCode SET dateFin=? WHERE id =?;");
        $sth->bindParam(1 , $value ,PDO::PARAM_STR );
        $sth->bindParam(2 , $id ,PDO::PARAM_INT , 10);
        return $sth->execute();
    }

    public static function UpdateCodeWithId(string $code , $id):bool{
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("UPDATE userSecurCode SET code=? WHERE id =?;");
        $sth->bindParam(1, $code, PDO::PARAM_STR, 255);
        $sth->bindParam(2 , $id ,PDO::PARAM_INT , 10);
        return $sth->execute();
    }

}
