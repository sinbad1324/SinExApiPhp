<?php

use Services\DB\DBConnection;

final class UserModel{

    public static function CreateManuelUser($data)  {
        $conn = DBConnection::GetConnection("sinox");
        $sth=$conn->prepare("INSERT INTO user(userName,email)")
    }
}
?>