<?php 

namespace Services\DB;

final class DBConnection{
    public $Connections;

    public  static function GetConnection(string $dbName){
        if (!isset(DBConnection::$Connections[$dbName])) {
            
        }
    }
}

?>
