<?php

class LogUtil {

    public static $log, $conn, $dir;

    public function __construct($conn, $Config){
        $dir = Config::get('dir');

        include $dir . 'lib/log4php/Logger.php';
        Logger::configure($dir . 'etc/log4php.xml');

//        TODO: Implement ERROR Type enum with SplEnum
//        include $dir . 'enum/error.php';

        self::$log = Logger::getLogger("main");
        self::$dir = $dir;
        self::$conn = $conn;
    }


    /**
     *
     * Log a username, level (or action), log-detail, and the exception_object so we can print the stack trace in the log
     *
     * @param $user IP = IP address, or pass in the fullName OR AuthenticationDAO::liFullName() for the Logged-In user
     * @param $level INFO, ACTION (Log to DB), WARN, ERROR (Logs Error Action to DB), FATAL (Logs Error Action to DB), 
     * DEBUG, TRACE action is logged as a site-action rather than a regular log
     * @param $details the details of the event logged
     * @param $exception_object = null The Exception object can be passed in so we can use the stack trace in the log
     */
    public function log($user, $level, $details, $exception_object = null, $ip_intrusion = null){
        /*
        if($userInput=='getLiUser'){
            try {
                $user = AuthenticationDAO::liFullName();

                //TODO: Decision: Possiby remove this and add logging to Auth
                // class instead and make the user just pass in the loggers username

            } catch(Exception $e) {
                echo 'You need to include the Authentication.php from the lib folder - ' . $e->getMessage();
            }
        } else {
            $user = $userInput;
        }
        */

        $level = strtoupper($level);
        if($user == 'IP')
        {
            $user = LogUtil::getIp();
        }
        
        $ip_intrusion = strtoupper($ip_intrusion);
        if($ip_intrusion == 'IP') {
            $ip_intrusion = LogUtil::getIp();
        }

        if(strtolower($level) == 'info')
        {
            self::$log->info($user . " - " . $details, $exception_object);
        } 
        else if(strtolower($level) == 'warn')
        {
            self::$log->warn($user . " - " . $details, $exception_object);
            self::logDb($user, $level, $details);
        } 
        else if(strtolower($level) == 'error')
        {
            self::$log->error($user . " - " . $details, $exception_object);
            self::logDb($user, $level, $details, $details); //TODO: Add the exception column to the db schema
        }
        else if(strtolower($level) == 'fatal')
        {
            self::$log->fatal($user . " - " . $details, $exception_object);
            self::logDb($user, $level, $details);
        }
        else if(strtolower($level) == 'debug')
        {
            self::$log->debug($user . " - " . $details, $exception_object, $exception_object);
        } 
        else if(strtolower($level) == 'action')
        {
            self::$log->info("Action: " . $action . " - " . $details, $exception_object);
            self::logDb($user, $level, $details, $action);
        }
        else if(strtolower($level) == 'trace') 
        {
            self::$log->trace($user . " - $details", $exception_object);
        } 
        else 
        {
            self::$log->warn($user . " - " . $details, $exception_object);
        }
    }

    //TODO: CHange IP_Intrusion to Intrusion_IP and add more columns:
    //  for example, file name, line number, stack-trace, or just 
    //  an exception dump so we cna re-load thie exception with this
    //  info. TODO: Also, add more Exception types extending Exception
   
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
