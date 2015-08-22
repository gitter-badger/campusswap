<?php
namespace Campusswap\DAO;

use Campusswap\Object\Authentication;

class AuthenticationDAO {

    public $Conn, $sql, $count, $Log, $dir, $Config, $Auth, $AuthSess, $Parser;

    /**
     * 
     * Access System and User Authentication Information, as well as data from
     * 
     * @param type $Conn connection
     * @param type $Config configuration
     * @param type $Log LogUtil
     * @param type $UsersDAO User DAO
     * @param type $Parser Parser Util
     * @throws AuthException Authorization Exception
     */
    public function __construct($Conn, $Config, $Log, $UsersDAO, $Parser) {

        $dir = $Config->get('dir');
        $this->dir = $dir;
        $this->Log = $Log;
        $this->Conn = $Conn;
        $this->Parser = $Parser;
        include $dir . 'lib/Object/Authentication.php'; //TODO: Remove these add Autoloader
        
        //include $dir . 'lib/Exception/AuthException.php';
        //include $dir . 'lib/Exception/IntrusionException.php';
        
        $AuthErrorLogDesc = "";
        try {
            if (isset($_SESSION['user']) && isset($_SESSION['domain']) && isset($_SESSION['userId']) && isset($_SESSION['level'])) {
                $AuthErrorLogDesc = 'AuthenticationDAO error - Session variables are not set correctly. Session Variables: '
                        . $_SESSION['userId'] . " "
                        . $_SESSION['user'] . " "
                        . $_SESSION['domain'] . " "
                        . $_SESSION['level'];

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
                $this->Auth->setIsLi(true);
                
                //TODO: Add a session table in DB and add a hash for each session

                //TODO: Replace with strcasecmp() for non-case-sensitive compare
                if(!isAuthEqual($this->AuthSess, $this->Auth)) {
                    throw new AuthException("Auth Error session variables are not set correctly");
                }
            } else {
                $this->Auth = new Authentication();
                $this->Auth->setliId(false);
                $this->Auth->setliUser(false);
                $this->Auth->setliDomain(false);
                $this->Auth->setLiFullName(false);
                $this->Auth->setliLevel(false);
                $this->Auth->setIsLi(false);
            }
        } catch (AuthException $ae) {
            $this->Auth = new Authentication();
            $this->Auth->setIsLi(false);
            $this->Log->log('IP', 'action', $ae->getMessage(), $ae);
            $Helper->print_error($ae->getInstruction());
        } catch (Exception $e) {
            $this->Auth = new Authentication();
            $this->Auth->setIsLi(false);
            $this->Log->log('IP', 'action', $e->getMessage(), $e);
            $Helper->print_error();
        }
    }
    
    public function isAuthEqual($auth1, $auth2) {
        if ($this->Parser->isEqual($auth1->getLiId(), $auth2->getLiId())) {
           return false;
        } elseif ($Parser->isEqual($auth1->getLiUser(), $auth2->getLiUser())) {
            return false;
        } elseif ($Parser->isEqual($auth1->getLiDomain(), $auth2->getLiDomain())) {
            return false;
        } elseif ($Parser->isEqual($auth1->getLiLevel(), $auth2->getLiLevel())) {
            return false; //todo - maybe add liFullName
        } else {
            return true;
        }
    }

    public function getAuthObject() {
        return $this->Auth;
    }

    public function isLi() { //TODO: Time this and see how expensive 
        if (isset($_SESSION['user']) && isset($_SESSION['domain']) && isset($_SESSION['level']) && isset($_SESSION['userId'])) {
            if ($this->Auth != null) {
                if ($this->Auth->getIsLi() != false) {
                    if ($_SESSION['user'] == $this->Auth->getLiUser()) {
                        if ($_SESSION['domain'] == $this->Auth->getLiDomain()) {
                            if ($_SESSION['level'] == $this->Auth->getLiLevel()) {
                                if ($_SESSION['userId'] == $this->Auth->getLiId()) {
                                    return true;
                                } else {
                                    return false;
                                }
                            } else {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>

