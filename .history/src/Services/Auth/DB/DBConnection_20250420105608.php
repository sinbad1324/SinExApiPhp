<?php 

namespace Services\DB;
use \PDO;
final class DBConnection{
    public static $Connections=[];
    public  static function GetConnection(string $dbName): PDO {
        if (!isset(DBConnection::$Connections[$dbName])) {
            DBConnection::$Connections[$dbName] = new PDO($_ENV["DB_MOTOR"].";host=".$_ENV["DB_HOST"].";dbname=$dbName" , $_ENV["DB_USER"],$_ENV["DB_PASSWORD"]);
            DBConnection::$Connections[$dbName]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return DBConnection::$Connections[$dbName];
    }
}

?>
