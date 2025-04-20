<?php 

namespace Services\DB;
use \PDO;
final class DBConnection{
    public $Connections;

    public  static function GetConnection(string $dbName): PDO {
        if (!isset(DBConnection::$Connections[$dbName])) {
            DBConnection::$Connections[$dbName] = new PDO($_ENV["DB_MOTOR"].";host=".$_ENV["DB_HOST"].";dbname=$dbName")
        }
    }
}

?>
