<?php

$college = ''; $college_string = '';
$sort = ''; $sort_string = '';
$search = ''; $search_string = '';

//URL Disection + Assignment
if(!isset($_GET)){
    
    if(AuthenticationDAO::isLi()){
        $college = $liDomain;
        $college_string = "false"; //Maybe this 'extra' 'string' version of the main variables
                                  // can be done better.. like a parser.. this is shitty
    } else {
        $college = 'all';
        $college_string = "false";
    }
    $sort = false;
    $sort_string = "false";

    $search = false;
    $search_string = "false";

    $item = false;
} else if(isset($_GET['item'])){
    $college = 'all';
    $college_string = "false";

    $sort = false;
    $sort_string = "false";

    $search = false;
    $search_string = "false";

    $item = $_GET['item'];
} else if(isset($_GET['college']) || isset($_GET['sort']) || isset($_GET['search'])){
    $item = false;

    if(isset($_GET['college'])){
        $college = Parser::sanitize($_GET['college']);
        $college_string = $college;
    } else {
        $college = 'all';
        $college_string = "false";
    }
    
    if(isset($_GET['search'])){
        $search = Parser::sanitize($_GET['search']);
        $search_string = $search;
    } else {
        $search = false;
        $search_string = "false";
    }
    
    if(isset($_GET['sort'])){
        if($_GET['sort']=='none'){
            $sort = 'none';
            $sort_string = "None";
        } else {
            $sort = Parser::sanitize($_GET['sort']);
            $sort_string = $sort;
        }
    } else {
        $sort = false;
        $sort_string = "false";
    }

} else {
    if(AuthenticationDAO::isLi()){
        $college = $liDomain;
        $college_string = $liDomain;
    } else {
        $college = 'all';
        $college_string = "false";
    }
    $sort = false;
    $sort_string = "false";

    $search = false;
    $search_string = "false";

    $item = false;
}

//Sort by Date Descending by DEFAULT
if(Parser::isFalse($sort)) {
    $sort = 'dateDesc';
}



?>
