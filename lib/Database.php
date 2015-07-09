<?php

class Database {
    
    private static $conn;
    public static $query;

    
    public function &query($sql){
        
        $connection = self::connection();
        
        $query = mysqli_query($connection, $sql);
        
        return self::$query;
        
    }

    //TODO: Implement Registry PHP Design Pattern
    public function &connection(){
        return self::$conn;
    }

    public function __construct(){

        if(class_exists('Config')){}
        else {
            include('./lib/Config.php');
            $Config = new Config('./etc/config.ini');
        }
        
        $server = Config::get('db_server');
        $user = Config::get('db_user');
        $password = Config::get('db_password');
        $database = Config::get('db_database');
         
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