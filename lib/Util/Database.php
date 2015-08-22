<?php

namespace Campusswap\Util;

class Database {
    
    private static $conn;
    public static $query;
    
    public $Config;



    public function &query($sql){
        
        $connection = self::connection();
        
        $query = mysqli_query($connection, $sql);
        
        return self::$query;
        
    }

    //TODO: Implement Registry PHP Design Pattern
    public function &connection(){
        return self::$conn;
    }
/**
 * Connect and Access MySql Database
 * @param type $Config
 * @return type
 */
    public function __construct($Config){

        $this->Config = $Config;
        
        $server = $Config->get('db_server');
        $user = $Config->get('db_user');
        $password = $Config->get('db_password');
        $database = $Config->get('db_database');
         
        self::$conn = mysqli_connect($server, $user, $password, $database);

        if (\mysqli_connect_error() || !self::$conn) {
            echo 'CampuSwap Error trying to connect to DB<br>';
            die('Connect Error (' . mysqli_connect_errno(self::$conn) . ') '
                    . mysqli_connect_error(self::$conn));
        } else {
            return self::$conn;
        }
    }
}



?>