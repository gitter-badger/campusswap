<?php
namespace Campusswap\DAO;

use \Campusswap\Object\Access;
/**
 * Data Access Object for the Access table. 
 * 
 * Access - each access object is a row in the Access table, mainly consisting
 * of an IP and a date. Usernames can be associated with an access, as well as 
 * failed login-attempts and page intrusions.
 * 
 */
class AccessDAO {

    public static $Conn, $Config, $count, $log, $UsersDAO, $AuthenticationDAO;
    public $access = false, $dir;

    /**
     * The Data Access Object for the Access system, Failed Login and Intrusion Systems.
     * @param type $Connection
     * @param type $Config
     * @param type $log
     * @param type $UsersDAO
     * @param type $AuthenticationDAO
     */
    public function __construct($Conn, $Config, $log, $UsersDAO, $AuthenticationDAO) {
        self::$Conn = $Conn;
        self::$Config = $Config;
        self::$log = $log;
        self::$UsersDAO = $UsersDAO;
        self::$AuthenticationDAO = $AuthenticationDAO->getAuthObject();
    }

    public function getAccess($ip = 'ip', $date = 'NOW') {

        if($this->access != false) {
            if(strcasecmp($ip, 'ip') == 0) {
                $ip = self::getIp();
            }

            $sql = $this->getAccessSql($ip, $date);

            $this->access = $this->createObject($sql);

            if(self::$AuthenticationDAO->isLi()) {
                if(!$access->hasUser($fullname)) {

                }
            }
        }

        if($this->access) {
            return $this->access;
        } else {
            return false;
            //$this->addAccess($ip, $date);
        }
    }
    
    public function addUser($userAdd) {
            if(strcasecmp($this->access->getUsernames(), "")!=0) { {
                $this->setUsernames($this->usernames . "/" . $userAdd);
                mysqli_query(self::$Conn, "UPDATE access SET usernames = " . $this->getSqlTodayClause());
            }
        }
    }
    
    public function getAccesses() {
        $sql = "SELECT * FROM access";
        $query = mysqli_query(self::$Conn, $sql);
        if($query) {
            $return = $this->createObject($sql);
        } else {
            $return = false;
        }
        return $return;
    }
    
    public function addAccess($ip = 'ip', $date = 'NOW') {
        try {
            if(!$this->getAccess($ip, $date)){
                if(strcasecmp($ip, 'ip') == 0) {
                    $ip = self::getIp();
                }

                if(strcasecmp($date, 'now') == 0) {
                    $sql = "INSERT INTO access (ip, usernames, status, visits, failed_logins, datetime) "
                                . "VALUES ('" . $ip . "', '', 'ok', 1, 0, NOW())";
                } else {
                    $sql = "INSERT INTO access (ip, usernames, status, visits, failed_logins, datetime) "
                                . "VALUES ('" . $ip . "', '', 'ok', 1, 0, '$date')";
                }
                
                $add_access_query = mysqli_query(self::$Conn, $sql);
                if(!$add_access_query) {
                    $this->log->log('ip', 'error', 'AccessDAO - Error inserting access record into access table');
                    return false;
                } else {
                    $result = $this->getAccess($ip, $date);

                    if($result) {
                        return $result;
                    } else {
                        throw new \Exception("AcecssDAO - could not load the previously INSERTED acecss statement");
                    }
                }
            } else  {
                throw new Exception("AcecssDAO - Access already exists");
            }
        } catch(\Exception $e) {
            //$this->log->log('IP', 'error', 'AccessDAO error - could not select access after creating it', $e);
            return false;
        }
    }
    
    public function getAccessId($ip = 'ip', $date = 'NOW') {
         if(strcasecmp($ip, 'ip') == 0) {
            $ip = self::getIp();
        }
        
        //$gidSql = "SELECT * FROM `access` WHERE `ip` = `" . $ip . "` AND " . $this->getSqlTodayClause($date);
        $gidSql = $this->getAccessSql($ip, $date);
        
        $gidObj = $this->createObject($gidSql);
        $gidId = $gidObj->getId();
        
        return $gidId;
    }
    
    public function addVisit($ip = 'ip', $date = 'NOW') {
        if(strcasecmp($ip, 'ip') == 0) {
            $ip = self::getIp();
        }
        
        //todo: this Update SQL does not actually update
        $addVisitSql = "UPDATE access "
                . "SET visits = visits + 1 "
                . "WHERE id = " . $this->getAccessId($ip, $date) . " ";
        $query = mysqli_query(self::$Conn, $addVisitSql);
        if($query) {
            $this->log->log('IP', 'info', 'AccessDAO - Added visit to ' + $ip);
            return true;
        } else {
            $this->log->log('IP', 'error', 'MySQL Error adding visit to Access: ' . mysqli_error(self::$Conn));
            return false;
        }
    }
    
    public function addFailedLogin($ip = 'ip') {
        if(strcasecmp($ip, 'ip') == 0) {
            $ip = self::getIp();
        }
        $sql = "UPDATE access "
                . "SET failed_logins = failed_logins + 1 "
                . "WHERE (ip = " . $ip . " AND " . $this->getSqlTodayClause();
        
        if(mysqli_query(self::$Conn, $sql)) {
            $this->log->log('IP', 'error', 'AccessDAO - Error updating access row and aupdating visit count');
            return true;
        } else {
            $this->log->log('IP', 'error', 'AccessDAO - Error updating access row and aupdating visit count');
            return false;
        }
    }
    
    public function addIntrusion($ip = 'ip', $fileName = false) {
        if(strcasecmp($ip, 'ip') == 0) {
            $ip = self::getIp();
        }
        
        if(!$fileName) {
            
        }
        
         $sql = "UPDATE access "
                . "SET `intrusions` = `intrusions` + 1 "
                . "WHERE (`ip` = " . $ip . " AND " . $this->getSqlToday();
        
        if(mysqli_query(self::$Conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function accessHasUsername($ip, $date, $username) {
        $sql = $this->getAccessSql($ip, $date);
        
        return $this->createObject($sql)->hasUsername($username);
    }
    
    public function accessAddUsername($ip, $username) {
        
    }
    public function getSqlTodayClause($date = 'NOW') {
        if(strcasecmp($date, 'NOW') == 0) {
            return "(`datetime` > DATE_SUB(now(), INTERVAL 1 DAY))";
        } else {
            return "(`datetime` > DATE_SUB(`" . $date . "`, INTERVAL 1 DAY))";
        }
    }
    public function getAccessSql($ip = 'ip', $date = 'NOW') {
        if(strcasecmp($ip, 'ip') == 0) {
            $ip = self::getIp();
        }
        if(strcasecmp($date, 'NOW') == 0) {
            return  $sql = "SELECT * FROM access WHERE (ip = '" . $ip . "' AND " . $this->getSqlTodayClause($date) . ")";
        } else {
            return  $sql = "SELECT * FROM access WHERE (ip = '" . $ip . "' AND " . $this->getSqlTodayClause($date) . ")";
        }
    }
    
    public static function getFilename() {
        return $basename = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF']))-4);
        //http://stackoverflow.com/questions/4672342/detecting-page-filename-using-php
    }
    
    public static function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){   //Check if IP from shared internet
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   //Check if IP passed from proxy
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    private function createObject($sql){

        $result = mysqli_query(self::$Conn, $sql);
        
        if(!$result) {
            $this->log->log($ip, 'fatal', 'MySQL Error selecting Access row: ' . mysqli_errno(self::$Conn));
            return false;
        }

        self::$count = mysqli_num_rows($result);

        if(self::$count > 0){
            if(self::$count > 1) {
                $this->log->log($ip, 'fatal', 'There are multiple Access entries for the same IP and Date period');
            }
            while($row = mysqli_fetch_array($result)){
                $Access = new Access();
                $Access->setId($row['id']);
                $Access->setIp($row['ip']);
                $Access->setUsernames($row['usernames']);
                $Access->setStatus($row['status']);
                $Access->setVisits($row['visits']);
                $Access->setFailedLogins($row['failed_logins']);
                $Access->setIntrusions($row['intrusions']);
                $Access->setDatetime($row['datetime']);
            }
            return $Access;
        } else {
            return false;
        }
    }
}