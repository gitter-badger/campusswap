<?php
//todo - write a python script that inserts all the
//              copyright and author info to the top of all files, (do last)
     
namespace Campusswap\Util;

/** 
 * Custom set of Parser Utilities that compliment the lacking useful PHP parsers
 *
 * @author vaskaloidis
 */
class Parser {

    public function isVarSet($input) {
        //todo - finish this
    }
    
    public function isVar($var) {
        try {
            if(isset($var)) {
                if(is_object($var)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
    }
    
    public function isEqual($str1, $str2) {
        try {
            if($this->isVar($str1) && $this->isVar($str2)) {
                //todo check if its a string or number or bool 
                if(strcasecmp(Strval($str1), strval($str2)) == 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch(Exception $e) {
            throw new $e;
        }
    }

    public function sanitize($input) {
        $strip_tags = strip_tags($input);
        $return = htmlentities($strip_tags);

        return $return;
    }

    public function isTrue($input){
        $yes = array('yes', 'y', 'true', '1');
        
        if(in_array(strtolower($input), $yes)){
            return TRUE;
        } else {
            return false;
        }
    }

    public function isFalse($input){
        $no = array('no', 'f', 'false');

        if(in_array(strtolower($input), $no) || $input == false){
            return true;
        } else {
            return false;
        }
    }

    public function getBoolean($input) {
        if($input == FALSE){
            return 'false';
        } else if($this->isFalse($input)) {
            return 'false';
        } elseif($this->isTrue($input)){
            return 'true';
        } else {
            return strval($input);
        }
    }

    public function getNumber($input) {
        if($this->isFalse($input) || is_bool($input)) {
            return 0;
        } elseif(is_numeric($input)){
            if(gettype($input) == 'float' || gettype($input) == 'double'){
                return floatval($input);
            } else if(gettype($input) == 'integer'){
                return intval($input);
            } else {
                return 0;
            }
        } else {
            return 0;
        }

    }

}
