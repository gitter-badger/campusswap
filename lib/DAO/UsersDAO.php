<?php

/**
 * User: vaskaloidis
 * Date: 11/5/14
 * Time: 2:37 AM
 */
//TODO: Implement the DAO interface here
namespace Campusswap\DAO;;
class UsersDAO {

    public static $conn, $Config, $log;
    public $count = 0;

    /**
     * The Data Access Object for the Users Table
     * @param type $connection
     * @param type $Config
     * @param type $log
     */
    public function __construct($connection, $Config, $log) {
        self::$conn = $connection;
        self::$Config = $Config;
        self::$log = $log;
        $dir = $Config->get('dir');
        include $dir . 'lib/Object/User.php';
        include $dir . 'lib/Exception/AuthException.php';
        include $dir . 'lib/Exception/IntrusionException.php';
    }

    public function createObject($sql) {

        $result = mysqli_query(self::$conn, $sql);

        $this->count = mysqli_num_rows($result);

        if ($this->count == 1) {
            while ($row = mysqli_fetch_array($result)) {
                $User = new Campusswap\Object\User();
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
            if($this->count > 1) {
                self::$log->log('IP', 'error', 'impossible result - UsersDAO found more than one result for query: ' . $sql );
            }
            return false;
        }
    }

    public function getUserFromId($id) {
        $sql = "SELECT * FROM users WHERE id = '" . $id . "'";

        return $this->createObject($sql);
    }

    public function getUserFromName($user, $domain) {
        $sql = "SELECT * FROM users WHERE username = '" . $user . "' AND domain='" . $domain . "' ";

        return $this->createObject($sql);
    }

    public static function getUsername($id) {
        $sql = "SELECT * FROM users WHERE $id = '$id' ";

        $result = mysqli_query(self::$conn, $sql);

        $count = mysqli_num_rows($result);

        if ($count > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $return = $row['username'] . '@' . $row['domain'];
            }
            return $return;
        }
    }

    //CREATE USER
    public function createUser($user, $domain, $password) {

        $sql = "INSERT INTO users
                        (id, username, password, domain, level, created, modified)
                    VALUES
                        (NULL, '$user', SHA('$password'), '$domain', 'normal', NOW(), NOW())";

        $result = mysqli_query(self::$conn, $sql);

        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function authenticateUser($user, $domain, $password) {
        $sql = "SELECT * FROM users "
                . "WHERE (username='$user' "
                . "AND domain='$domain' "
                . "AND password=SHA('$password'))";

        self::$log->log('IP', 'debug', 'authenticateUser SQL: ' . $sql);
        
        $userObject = $this->createObject($sql);

        if($userObject) {
            if ($userObject->getStatus() == 'banned') {
                throw new IntrusionException("banned user attempt login - " + $user + "@" + $domain);
            }

            if ($this->count != 1) {
                //todo - possibly re-implement this here too. Moved to createObject()
//                self::$log->log('IP', 'error', 'impossible result - ' . $user . '@' . $domain . ' auth attempt returned ' . $this->count . ' rows ');
            }
            return $userObject;
        } else {
            throw new AuthException("Incorrect Credentials", "You did not enter a correct username or password, please try again. Note that you will be banned for 24 hours after too-many failed login attempts, and eventually your username and / or IP may become banned permenantly.");
        }
    }

    public function updatePassword($username, $domain, $password) {
        $sql = "UPDATE users SET password = SHA('$password') WHERE username='$username' AND domain='$domain'";

        $query = mysqli_query(self::$conn, $sql);

        return $query;
    }

    //SEE IF USER EXISTS ALREADY
    public static function userExists($user, $domain, $conn) {

        $result = mysqli_query($conn, "SELECT *
							FROM users
							WHERE username = '" . $user . "' " .
                " AND domain = '" . $domain . "' ");
        $num_rows = mysqli_num_rows($result);
        if ($num_rows == 1) {
            return TRUE;
        } else if ($num_rows > 1) {
            self::$log->log('IP', 'FATAL', $user . '@' . $domain . ' returned multiple results from the database');
            return true;
        } else {
            return FALSE;
        }
    }

}
