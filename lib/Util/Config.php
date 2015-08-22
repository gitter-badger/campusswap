<?php

namespace Campusswap\Util;

class Config{
    
    private static $dir, $section;
    
    function __autoload($class) {
        $namespace = str_replace('\\', '/', __NAMESPACE__);
        require __DIR__ . '/' . $namespace . '/' . $class . '.php';
    }

    public function getOpt($input){

        if(!isset($parsed)){
            $ini = parse_ini_file(self::$dir, true);
        }

        $CONFIG = $ini[self::$section];

        return $CONFIG[$input];
    }
    
    public function get($input){

        if(!isset($parsed)){
            $ini = parse_ini_file(self::$dir, true);
        }
		
        $CONFIG = $ini[self::$section];
        
        return $CONFIG[$input];
    }

//    public static function get($input){
//
//        if(!isset($parsed)){
//            $ini = parse_ini_file(self::$dir, true);
//        }
//		
//        $CONFIG = $ini[self::$section];
//        
//        return $CONFIG[$input];
//    }
    
    public function __construct($dir){
        self::$dir = $dir;

        // DECLARE ENVIORMENT HERE
        self::$section = 'CONFIG';
    }

}

?>
