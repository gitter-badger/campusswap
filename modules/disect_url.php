<?php

$college = ''; $college_string = '';
$sort = ''; $sort_string = '';
$search = ''; $search_string = '';

//URL Disection + Assignment
if(!isset($_GET)){
    
    if(Authentication::isLi()){
        $college = $liDomain;
        $college_string = "false"; //Maybe this extra 'string' version of the vars can be done better.. like a parser..shit
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
        $college = $_GET['college'];
    } else {
        $college = 'all';
        $college_string = "false";
    }
    
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    } else {
        $search = false;
        $search_string = "false";
    }
    
    if(isset($_GET['sort'])){
        if($_GET['sort']=='none'){
            $sort = false;
            $sort_string = "false";
        } else {
            $sort = $_GET['sort'];
        }
    } else {
        $sort = false;
        $sort_string = "false";
    }

} else {
    if(Authentication::isLi()){
        $college = $liDomain;
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
