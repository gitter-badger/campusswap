<?php
/**
 * Created by PhpStorm.
 * User: vaskaloidis
 * Date: 11/5/14
 * Time: 2:37 AM
 */

class UsersDAO {

    public static $conn, $config, $count, $log;

    public function __construct($connection, $config, $log) {
        self::$conn = $connection;
        self::$config = $config;
        self::$log = $log;
        $dir = Config::get('dir');
        include $dir . 'lib/Objects/User.php';
    }

    private function createObject($sql){

        $result = mysqli_query(self::$conn, $sql);

        self::$count = mysqli_num_rows($result);

        if(self::$count == 1){
            while($row = mysqli_fetch_array($result)){
                $User = new User();
                $User->setId($row['id']);
                $User->setUsername($row['username']);
                $User->setDomain($row['domain']);
                $User->setPassword($row['password']);
                $User->setLevel($row['level']);
                $User->setLikes($row['likes']);
                $User->setCreated($row['created']);
                $User->setModified($row['modified']);
                $User->setStatus($row['status']);
            }
            return $User;
        } else {
            return false;
        }
    }

    public function getUserFromId($id){
        $sql = "SELECT * FROM users WHERE id = '" . $id . "'";

        return $this->createObject($sql);
    }

    public function getUserFromName($user, $domain){
        $sql = "SELECT * FROM users WHERE username = '" . $user . "' AND domain='" . $domain . "' ";

        return $this->createObject($sql);
    }

    public static function getUsername($id) {
        $sql = "SELECT * FROM users WHERE $id = '$id' ";

        $result = mysqli_query(self::$conn, $sql);

        $count = mysqli_num_rows($result);

        if($count > 0) {
            while($row = mysqli_fetch_array($result)) {
                $return = $row['username'] . '@' . $row['domain'];
            }
            return $return;
        }
    }

    //CREATE USER
    public function createUser($user, $domain, $password){

        $sql = "INSERT INTO users
                        (id, username, password, domain, level, created, modified)
                    VALUES
                        (NULL, '$user', SHA('$password'), '$domain', 'normal', NOW(), NOW())";

        $result = mysqli_query(self::$conn, $sql);

        if($result){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function authenticateUser($user, $domain, $password){
        $sql = "SELECT * FROM users WHERE (username='$user' AND domain='$domain' AND password=SHA('$password'))";

        $user = $this->createObject($sql);

        if($user->getStatus() == 'banned'){
            return 'banned';
        }

        if(self::$count < 1 || Parser::isFalse(self::$count)) {
            return FALSE;
        }

        if(self::$count > 1) {
            self::$log->log('IP', 'FATAL', $user . '@' . $domain . ' returned multiple results from the database');
        }
        return $user;
    }

    //SEE IF USER EXISTS ALREADY
    public static function userExists($user, $domain, $conn){

        $result = mysqli_query($conn, "SELECT *
							FROM users
							WHERE username = '" . $user . "' " .
                            " AND domain = '" . $domain . "' ");
        $num_rows = mysqli_num_rows($result);
        if($num_rows == 1) {
            return TRUE;
        } else if($num_rows > 1){
            self::$log->log('IP', 'FATAL', $user . '@' . $domain . ' returned multiple results from the database');
            return true;
        } else {
            return FALSE;
        }
    }

} 