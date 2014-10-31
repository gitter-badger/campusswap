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
    public static function isTrue($input){
        $yes = array('yes', 'y', 'true');
        
        if(in_array(strtolower($input), $yes) || $input == true){
            return true;
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

}
