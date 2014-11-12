<?php


class AuthenticationDAO {

    public static $conn, $sql, $count, $log, $dir;
    public $Auth;

    public function __construct($config) {

        $dir = Config::get('dir');
        self::$dir = $dir;
        include $dir . 'lib/Objects/Authentication.php';

        //Create the Authentication Object
        $this->Auth = new Authentication();
        $this->Auth->setIsLi(Parser::isTrue(self::isLi()));
        $this->Auth->setliId(self::liId());
        $this->Auth->setliUser(self::liUser());
        $this->Auth->setliDomain(self::liDomain());
        $this->Auth->setLiFullName(self::liFullName());
        $this->Auth->setIsAdmin(self::isAdmin());
        $this->Auth->setliLevel(self::liLevel());

    }

    public function getAuthObject() {
        return $this->Auth;
    }
    
    public static function isLi(){ //TODO: Lock this down!
        if(isset($_SESSION['user']) && isset($_SESSION['domain'])){
            return true;
        } else {
            return false;
        }
    }

    public static function isAdmin(){
        if(isset($_SESSION['level'])){
            return true;
        } else {
            return false;
        }
    }

    public static function liUser(){
        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        } else {
            return null;
        }

    }

    public static function liId(){
        if(isset($_SESSION['userId'])){
            return $_SESSION['userId'];
        } else {
            return null;
        }
    }

    public static function liLevel(){
        if(isset($_SESSION['level'])){
            return $_SESSION['level'];
        } else {
            return null;
        }
    }

    public static function liDomain(){
        if(isset($_SESSION['domain'])){
            return $_SESSION['domain'];
        } else {
            return null;
        }
    }

    public static function liFullName(){
        if(isset($_SESSION['user']) && isset($_SESSION['domain'])){
            $return = $_SESSION['user'] . '@' . $_SESSION['domain'];
        } else {
            $return = null;
        }
        return $return;
    }
    
    
    
}





?>

