<?php

include('./functions.php');



if(isset($_GET['id'])){
	
	$id = $_GET['id'];
	$result = mysql_query("UPDATE posts SET views = views +1 WHERE id = '$id'");
}

?>