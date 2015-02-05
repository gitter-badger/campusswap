<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Parser
 *
 * @author vaskaloidis
 */
class Parser {

    public static function isVarSet($input) {

    }

    public static function sanitize($input) {
        $strip_tags = strip_tags($input);
        $return = htmlentities($strip_tags);

        return $return;
    }

    public static function isTrue($input){
        $yes = array('yes', 'y', 'true', '1');
        
        if(in_array(strtolower($input), $yes)){
            return TRUE;
        } else {
            return false;
        }
    }

    public static function isFalse($input){
        $no = array('no', 'f', 'false');

        if(in_array(strtolower($input), $no) || $input == false){
            return true;
        } else {
            return false;
        }
    }

    public static function getBoolean($input) {
        if($input == FALSE){
            return 'false';
        } else if(Parser::isFalse($input)) {
            return 'false';
        } elseif(Parser::isTrue($input)){
            return 'true';
        } else {
            return strval($input);
        }
    }

    public static function getNumber($input) {
        if(Parser::isFalse($input) || is_bool($input)) {
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
