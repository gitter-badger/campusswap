<?php


class AuthenticationDAO {

    public static $conn, $sql, $count, $log, $dir;
    public $Auth, $AuthSess;

    public function __construct(mysqli $Conn, Config $Config, LogUtil $log, UsersDAO $UsersDAO) {

        $dir = Config::get('dir');
        self::$dir = $dir;
        self::$log = $log;
        include $dir . 'lib/Objects/Authentication.php';
        
        
        $AuthException = new Exception("authentication error");
        
        $AuthErrorLogDesc = 'authentication error - AuthenticationDAO - Session variables are not set correctly. Session Variables: '
            . $_SESSION['userId'] . " "
            . $_SESSION['user']  . " "
            . $_SESSION['domain'] . " "
            . $_SESSION['level'];
        
        try {
            if(isset($_SESSION['user']) 
                && isset($_SESSION['domain'])
                && isset($_SESSION['userId'])
                && isset($_SESSION['level']))
            {
                //Create the Authentication Object
                $this->AuthSess = new Authentication();
                $this->AuthSess->setliId($_SESSION['userId']);
                $this->AuthSess->setliUser($_SESSION['user']);
                $this->AuthSess->setliDomain($_SESSION['domain']);
                $this->AuthSess->setLiFullName($_SESSION['user'] . "@" . $_SESSION['domain']);
                $this->AuthSess->setliLevel($_SESSION['level']);

                $AuthenticatedUser = $UsersDAO->getUserFromId($_SESSION['userId']); //TDOO: User ID in Session is a soft-spot
                $this->Auth = new Authentication();
                $this->Auth->setliId($AuthenticatedUser->getId());
                $this->Auth->setliUser($AuthenticatedUser->getUsername());
                $this->Auth->setliDomain($AuthenticatedUser->getDomain());
                $this->Auth->setLiFullName($AuthenticatedUser->getFullName());
                $this->Auth->setliLevel($AuthenticatedUser->getLevel());

                //TODO: Replace with strcasecmp() for non-case-sensitive compare
                if($this->AuthSess->getLiId() != $this->AuthSess->getLiId()) {
                    throw new $AuthException;
                } elseif($this->AuthSess->getLiUser() != $this->AuthSess->getLiUser()) {
                    throw new $AuthException;
                } elseif($this->AuthSess->getLiDomain() != $this->AuthSess->getLiDomain()) {
                    throw new $AuthException;
                } elseif($this->AuthSess->getLiLevel() != $this->AuthSess->getLiLevel()) {
                    throw new $AuthException;
                } elseif($this->AuthSess->getLiLevel() != $this->AuthSess->getLiLevel()) {
                    throw new $AuthException;
                } else {
                    self::$log->log('IP', 'action', $AuthErrorLogDesc, $AuthException, 'IP');
                 }

            } else {
                Throw new $AuthException;
            }
        } catch(Exception $e) {
            self::$log->log('IP', 'action', $AuthErrorLogDesc, $e, 'IP');
            Helper::print_error("Sorry but we are having technical difficulties");
        }

    }

    public function getAuthObject() {
        return $this->Auth;
    }
    
    public static function isLi(){ //TODO: Lock this down!
        if(isset($_SESSION['user']) && isset($_SESSION['domain'])){
            if($_SESSION['user'] == $this->Auth->getLiUser())
            return true;
        } else {
            return false;
        }
    }

    public static function liUser(){
        return $this->Auth->getLiUser();

    }

    public static function liId(){
        return $this->Auth->getLiId();
    }

    public static function liLevel(){
        return $this->Auth->getLiUser();
    }
    
    public static function isAdmin() {
        if(liLevel() == 'admin') {
            return true;
        } else {
            return false;
        }
    }
    
    public static function isModerator() {
        if(liLevel() == 'moderator') {
            return true;
        } else {
            return false;
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

