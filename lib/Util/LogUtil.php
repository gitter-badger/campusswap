<?php

/**
 * Description of Log
 *
 * @author vaskaloidis
 */
class LogUtil {

    public static $log, $conn, $dir;

    public function __construct($conn, $config){
        $dir = Config::get('dir');

        include $dir . 'lib/log4php/Logger.php';
        Logger::configure($dir . 'etc/log4php.xml');

        self::$log = Logger::getLogger("main");
        self::$dir = $dir;
        self::$conn = $conn;
    }


    public function log($user, $level = 'action', $details, $action = null){
        /*
        if($userInput=='getLiUser'){
            try {
                $user = AuthenticationDAO::liFullName(); //TODO: *** START HERE ** Possiby remove this and add logging to Auth
                                                         // class instead and make the user just pass in the loggers username
            } catch(Exception $e) {
                echo 'You need to include the Authentication.php from the lib folder - ' . $e->getMessage();
            }
        } else {
            $user = $userInput;
        }
        */

        if(strtoupper($user) == 'IP'){
            $user = LogUtil::getIp();
        }

        $level = strtoupper($level);
        if($action != null) {
            $action = strtoupper($action);
        }

        if($level == 'INFO'){
            self::$log->info($user . " - " . $details);
        } else if($level == 'WARN'){
            self::$log->warn($user . " - " . $details);
            self::logDb($user, $level, $details);
        } else if($level == 'ERROR'){
            self::$log->error($user . " - " . $details);
            self::logDb($user, $level, $details, $action);
        } else if($level == 'FATAL'){
            self::$log->fatal($user . " - " . $details);
            self::logDb($user, $level, $details);
        } else if($level == 'DEBUG'){
            self::$log->debug($user . " - " . $details);
        } else if($level == 'ACTION') {
            self::$log->info("ACTION: " . $action . " - " . $details);
            self::logDb($user, $level, $details, $action);
        } else if($level == 'TRACE') {
            self::$log->trace($user . " - $details");
        } else {
            self::$log->warn($user . " - (**unknown log level set) - " . $details);
        }

    }

    public function logDb($user, $level, $details, $action = null) {
        if($action == null){
            $sql = "INSERT INTO log (user, level, details, date, time)
                VALUES ('$user', '$level', '$details', NOW(), NOW())";
        } else {
            $sql = "INSERT INTO log (user, level, details, action, date)
                VALUES ('$user', '$level', '$details', $action, NOW())";
        }

        mysqli_query(self::$conn, $sql);
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
    
}

?>
