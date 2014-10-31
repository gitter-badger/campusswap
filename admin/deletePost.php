<?php
include('../functions.php');



$id = $_POST['id'];

if(isset($_POST['deleteYES'])){
	
	$username = $_POST['username'];
	$domain = $_POST['domain'];
	
	$likeQuery = mysql_query("SELECT likes, username, domain FROM users");
	
	while($row = mysql_fetch_array($likeQuery)){
		echo $row[0] . '<br />';
		$likes = explode("/", $row[0]);
		$likesString = "";
		foreach($likes as $i){
			if($i == $id){
				//Do nothing
				echo 'Skipped adding ' . $i . ' to the likes<br />';
			} else {
				$likesString = $likesString . '/' . $i;
				echo $likesString . '<br />';
			}
		}
		mysql_query("UPDATE users WHERE id = '$id' SET likes = '$likesString'");
		echo 'Updated ' . $row[1] . '@' . $row[2] . ' and set likes to ' . $likesString . ' <br />';
	}
	
	
	//$postQuery = mysql_query("SELECT id FROM posts WHERE username = '$username' and domain = '$domain'");
	mysql_query("DELETE FROM posts WHERE id = '$id'");
	
	echo '<center>Post deleted</center>';
	
} else {
	

$query = mysql_query("SELECT item, username, domain FROM posts WHERE id = '$id' ");

$array = mysql_fetch_array($query);

echo '<center>Are you sure you would like to delete' . $array[0] . '<br />';
echo '<form action="deletePost.php" method="POST" />';
echo '<input type="hidden" name="deleteYES" value="TRUE" />';
echo '<input type="hidden" name="id" value="' . $id . '" />';
echo '<input type="hidden" name="username" value="' . $array[1] . '" />';
echo '<input type="hidden" name="domain" value="' . $array[2] . '" />';
echo '<input type="submit" value="Yes" />';
echo '</form></center>';
}
?>