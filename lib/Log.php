<?php

/**
 * Description of Log
 *
 * @author vaskaloidis
 */
class Log {
    
    public static function logDebug($message){
        Logger::configure(DIR . 'etc/log4php.xml');
        $logger = Logger::getLogger("main");
        
        $logger->debug($message);
        
    }

    public static function logAction($u, $a){
        
        //include('./lib/Config.php');
        
        //$config = new Config('./etc/config.ini');
        
        $dir = Config::get('dir');
	
        include($dir . 'lib/log4php/Logger.php');

        $logger = Logger::getLogger("main");

        Logger::configure($dir . 'etc/log4php.xml');

        $logger->warn($u . " - " . $a);
    
        //$sql = "INSERT INTO log (user, action, date, time) VALUES ('$u', '$a', NOW(), NOW())";
        //mysqli_query($conn, $sql);
}
    
    
}
