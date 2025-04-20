<?php

use Services\DB\DBConnection;

final class UserModel{

    public static function CreateManuelUser($data)  {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("INSERT INTO user(userName,email,password,createdTime)VALUES(?,?,?,'NOW()')");
        $sth->bindParam(1 , $data["userName"] ,PDO::PARAM_STR , 50);
        $sth->bindParam(2 , $data["email"] ,PDO::PARAM_STR , 255);
        $options = [
            // Increase the bcrypt cost from 12 to 13.
            'cost' => 13,
        ];
        $sth->bindParam(3 , password_hash("rasmuslerdorf", PASSWORD_BCRYPT, ['cost' => 13])  ,PDO::PARAM_STR , 150);
    }
}
?>