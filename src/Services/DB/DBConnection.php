<?php 

namespace Services\DB;
use \PDO;
final class DBConnection{
    public static $Connections=[];
    public  static function GetConnection(string $dbName): PDO {
        if (!isset(DBConnection::$Connections["mysite"])) {
            DBConnection::$Connections["mysite"] = new PDO($_ENV["DB_MOTOR"].":host=".$_ENV["DB_HOST"].";dbname=mysite" , $_ENV["DB_USER"],$_ENV["DB_PASSWORD"]);
            DBConnection::$Connections["mysite"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return DBConnection::$Connections["mysite"];
    }
}

?>
