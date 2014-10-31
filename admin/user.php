<?php
include('./adminHead.php');



if(isset($_GET['user']) && $_GET['user']!=null && $_GET['user']!="" ){
	
	$getUser = $_GET['user'];
	
	$userArray = explode("@", $getUser);
	
	$u = $userArray[0];
	$d = $userArray[1];
	
	$postQuery = mysql_query("SELECT * FROM posts WHERE username = '$u' AND domain = '$d'");
	$logQuery = mysql_query("SELECT * FROM log WHERE user = '$getUser'");
	
	$postCount = mysql_num_rows($postQuery);
	
	echo '<h1>User ' . $u . '</h1><br />';
	
	
	echo '<b>Posts</b><br />';
	echo 'Total Posts: (' . $postCount . ')';
	
	echo '<table style="width:1200px">';
	echo '<tr><td>id</td><td>title</td><td>price</td><td>hits</td></tr>';
	
	while($row = mysql_fetch_assoc($postQuery)){
		echo '<tr>';
		echo "<td>" . $row['id'] . "</td><td>" . $row['item'] . "</td><td>" . $row['price'] . "</td><td>" . $row['hits'] . "</td>";
		echo '</tr>';
	}
	echo '</table>';
	
	echo '<br /><b>Log Records</b><br />';
	
	echo '<table style="width:1200px">';
	echo '<tr><td>action</td><td>date</td></tr>';
	
	while($row = mysql_fetch_assoc($logQuery)){
		echo '<tr>';
		echo "<td>" . $row['action'] . "</td><td>" . $row['date'] . "</td>";
		echo '</tr>';
	}
	echo '</table>';



} else {
		echo '<center>';
	echo 'you need to enter a user';
	echo '<form action="user.php" method="get">';
	echo '<input type="text" name="user" />';
	echo '<input type="submit" value="submit" />';
		echo '</center>';
	
}


include('./adminFoot.php');

?>