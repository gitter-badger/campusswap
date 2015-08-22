<?php

namespace Campusswap\Util;

class LogUtil {

    public $Log, $Conn, $dir, $Parser;

    /**
     * A Utility Class for logging User and System Logs and Actions
     * @param type $Conn
     * @param type $Config
     */
    public function __construct($Conn, $Config, $Parser){
        $dir = $Config->get('dir');
        $this->Parser = $Parser;
        //TODO: re-configure log4php or go to composer alternative w. namespace
        //include $dir . 'lib/log4php/Logger.php';
        //Logger::configure($dir . 'etc/log4php.xml');
        
//        TODO: Implement ERROR Type enum with SplEnum (possibly)
//        include $dir . 'enum/error.php';

        //$this->log = Logger::getLogger("main");
        $this->dir = $dir;
        $this->conn = $Conn;
    }
    


    /**
     *
     * Log a username, level (or action), log-detail, and the exception_object so we can print the stack trace in the log
     *
     * @param $user IP = IP address, or pass in the fullName OR $AuthenticationDAO->getLiFullName() for the Logged-In user
     * @param $level INFO, ACTION (Log to DB), WARN, ERROR (Logs Error Action to DB), FATAL (Logs Error Action to DB), 
     * DEBUG, TRACE action is logged as a site-action rather than a regular log
     * @param $details the details of the event logged
     * @param $exception_object = null The Exception object can be passed in so we can use the stack trace in the log
     */
    public function log($user, $level, $details, $exception_object = null, $access = null){
        /*
        todo - refactor all log statements
        todo - remove $user from param and get user from SESSION Vars
        todo - plan out new log param structure
        try {
            $user = $this->getUser();
        } catch(Exception $e) {
            echo 'You need to include the Authentication.php from the lib folder - ' . $e->getMessage();
        }
        */
        
        if(strcasecmp($user, 'USER') == 0) {
            $user = $this->getUser();
        }

        if(strcasecmp($user, 'IP') == 0){
            $user = $this->getIp();
        }
        
        if($access != null) {
            $access_msg = "Access: " . $access->getIp()
                    . " " . $access->getStatus()
                    . " failed_logins: " . $access->getFailedLogins()
                    . " intrusions: " . $access->getIntrusions()
                    . " " . $access->getDatetime();
        }

        if($this->Parser->isEqual($level, 'info'))
        {
            $details = $details . " " . $access_msg;
            //$this->log->info($user . " - " . $details, $exception_object);
        } 
        else if($this->Parser->isEqual($level, 'warn'))
        {
            //$this->log->warn($user . " - " . $details, $exception_object);
            self::logDb($user, $level, $details);
        } 
        else if($this->Parser->isEqual($level, 'error'))
        {
            //$this->log->error($user . " - " . $details . " " . $access_msg, $exception_object);
            self::logDb($user, $level, $details, $details, $access); //TODO: Add the exception column to the db schema
        }
        else if($this->Parser->isEqual($level, 'fatal'))
        {
            //$this->log->fatal($user . " - " . $details . " " . $access_msg, $exception_object);
            self::logDb($user, $level, $details, $access);
        }
        else if($this->Parser->isEqual($level, 'debug'))
        {
            $details = $details . " " . $access_msg;
            //$this->log->debug($user . " - " . $details, $exception_object, $exception_object);
        } 
        else if($this->Parser->isEqual($level, 'action'))
        {
            //$this->log->info("Action: " . $details . " " . $access_msg, $exception_object);
            self::logDb($user, $level, $details, $access);
        }
        else if($this->Parser->isEqual($level, 'trace') )
        {
            $details = $details . " " . $access_msg;
            //$this->log->trace($user . " - $details", $exception_object);
        } 
        else 
        {
            //$this->log->warn($user . " - " . $details, $exception_object);
        }
    }

    //TODO: CHange IP_Intrusion to Intrusion_IP and add more columns:
    //  for example, file name, line number, stack-trace, or just 
    //  an exception dump so we cna re-load thie exception with this
    //  info. TODO: Also, add more Exception types extending Exception
   
    public function logDb($user, $level, $details, $access = null) { 
        if($access == null){
            $sql = "INSERT INTO log (user, level, details, date, time)
                VALUES ('$user', '$level', '$details', NOW(), NOW())";
        } else {
            $sql = "INSERT INTO log (user, level, details, ccess, date)
                VALUES ('$user', '$level', '$details', $access, NOW())";
        }

        mysqli_query($this->conn, $sql);
    }
    
    public function getUser() {
        if(!empty($_SESSION['fullName'])) {
            return $_SESSION['fullName'];
        } else {
            return self::getIp();
        }
    }

    public function getIp() {
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
    
}

?>
