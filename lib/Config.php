<?php


class Config{
    
    private static $dir, $system;

    public function getOpt($input){

        if(!isset($parsed)){
            $ini = parse_ini_file(self::$dir, true);
        }

        $CONFIG = $ini[self::$system];

        return $CONFIG[$input];
    }

    public static function get($input){

        if(!isset($parsed)){
            $ini = parse_ini_file(self::$dir, true);
        }
		
        $CONFIG = $ini[self::$system];
        
        return $CONFIG[$input];
    }
    
    public function __construct($dir){
        self::$dir = $dir;

        //DECLARE ENVIORMENT HERE
        // CONFIG_DEV OR CONFIG_PRODUCTION
        self::$system = 'CONFIG_DEV';
    }

}

?>
