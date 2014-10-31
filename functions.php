<?php

function return_home(){
    include('./theme/return_home.php');
}

function getSetup(){
	$setup = 'server'; //DATABSE & URL CONFIG
	return $setup;	
}


$version = '0.2'; //APP VERSION

$debug = FALSE; //DEBUG BAR

function getUrl(){
	
	$setup = getSetup();
	
	if($setup=='local'){
		return 'http://localhost:8888/campusswap/';
	} else {
		return 'http://www.campusSwap.net/';
	}
}

function loggedIn(){
	
	if(isset($_SESSION['user'])){
			$loggedIn = TRUE;
	} else {
			$loggedIn = FALSE;
	}

	return $loggedIn;
}

function logAction($u, $a){
	$sql = "INSERT INTO log (user, action, date, time) VALUES ('$u', '$a', NOW(), NOW())";
	mysql_query($sql);
}

function isLi(){
	if(isset($_SESSION['user']) && isset($_SESSION['domain'])){
		return true;
	} else {
		return false;
	}
}

function isAdmin(){
	if(isset($_SESSION['level'])){
		return true;
	} else {
		return false;
	}
}

function liUser(){
	if(isset($_SESSION['user'])){
		return $_SESSION['user'];
	} else {
		return null;
	}
	
}

function liId(){
	if(isset($_SESSION['userId'])){
		return $_SESSION['userId'];
	} else {
		return null;
	}
}

function liLevel(){
	if(isset($_SESSION['level'])){
		return $_SESSION['level'];
	} else {
		return null;
	}
}

function liDomain(){
	if(isset($_SESSION['domain'])){
		return $_SESSION['domain'];
	} else {
		return null;
	}
}

function liFullname(){
	if(isset($_SESSION['user']) && isset($_SESSION['domain'])){
		$return = $_SESSION['user'] . '@' . $_SESSION['domain'];
	} else {
		$return = null;
	}
	return $return;
}

function likes(){
	$liId = liId();
	
	$likesQuery = mysql_query("SELECT likes FROM users WHERE id = '$liId'");
	
	$likesArray = mysql_fetch_array($likesQuery);
	
	$likes = explode("/", $likesArray[0]);
	
	return $likes;
}

function getCollegeName($req){
	$nameQuery = mysql_query("SELECT name FROM domains WHERE domain = '$req'");
	
	$nameQueryArray = mysql_fetch_assoc($nameQuery);
	
	$toReturn = $nameQueryArray['name'];
	
	return $toReturn;
}

function is_page($what){
	if($what=='index' && $url=='index'){
		return true;
	}
	
}

function getDomain($user){
	$userPieces = explode("@", $user);
	
	return $userPieces[1];
}


function item(){
	return $item;
}

?>
