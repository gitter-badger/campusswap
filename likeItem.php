<?php

include('./functions.php');



if(isset($_GET['id'])){
	
	$id = $_GET['id'];
	$result = mysql_query("UPDATE posts SET hits = hits +1 WHERE id = '$id'");
	
	$update = '/' . $id;
	
	$userID = Authentication::liId();
	$result2 = mysql_query("UPDATE users SET likes = CONCAT(likes, '$update') WHERE id = '$userID'");
}

?>