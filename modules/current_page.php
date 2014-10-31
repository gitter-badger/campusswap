<?php

//POSSIBLY $_SERVERT php_self as parameter

$file = basename($_SERVER['REQUEST_URI']);

$title = '<title>Campus Swap - ';

switch($file){
    
    case 'index.php':
        $current_page = 'Home';
        
        $title = $title . 'Home';
        break;
    case 'signup.php':
        $current_page = 'Signup';
        $title = $title . $current_page;
        break;
    case 'passChoose.php':
        $current_page = 'Choose a Password';
        $title = $title . $current_page;
        break;
    case 'login.php':
        $current_page = 'Login';
        $title = $title . $current_page;
        break;
    default:
        $current_page = 'Home';
        $title = $title . $current_page;
}

$title = $title . '</title>';

?>
