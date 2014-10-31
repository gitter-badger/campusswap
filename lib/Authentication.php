<?php


class Authentication {
    
    public static function isLi(){
        //TODO: Lock this down!
            if(isset($_SESSION['user']) && isset($_SESSION['domain'])){
                    return true;
            } else {
                    return false;
            }
    }

    public static function isAdmin(){
            if(isset($_SESSION['level'])){
                    return true;
            } else {
                    return false;
            }
    }

    public static function liUser(){
            if(isset($_SESSION['user'])){
                    return $_SESSION['user'];
            } else {
                    return null;
            }

    }

    public static function liId(){
            if(isset($_SESSION['userId'])){
                    return $_SESSION['userId'];
            } else {
                    return null;
            }
    }

    public static function liLevel(){
            if(isset($_SESSION['level'])){
                    return $_SESSION['level'];
            } else {
                    return null;
            }
    }

    public static function liDomain(){
            if(isset($_SESSION['domain'])){
                    return $_SESSION['domain'];
            } else {
                    return null;
            }
    }

    public static function liFullname(){
            if(isset($_SESSION['user']) && isset($_SESSION['domain'])){
                    $return = $_SESSION['user'] . '@' . $_SESSION['domain'];
            } else {
                    $return = null;
            }
            return $return;
    }
    
    
    
}





?>

