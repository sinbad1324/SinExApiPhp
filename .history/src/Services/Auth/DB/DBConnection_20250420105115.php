<?php 

namespace Services\DB;

final class DBConnection{
    public $Connections;

    public  staic function GetConnection(string $dbName){
        if (!isset(DBConnection::$Connections[$dbName])) {
            # code...
        }
    }
}

?>
