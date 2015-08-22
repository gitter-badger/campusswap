<?php
include('./adminHead.php');
echo '<h1>Users</h1>';

if(isset($_POST['delete'])){
	$username = $_POST['username'];
	$domain = $_POST['domain'];
	
	echo '<center>Are you sure you would like to delete ' . $username . '@' . $domain . '</center>';
	
} else {

	$result = mysql_query("SELECT * FROM users");

	echo '<table class="table table-striped table-bordered table-condensed">';

	echo '<tr><td>id</td><td>email</td><td>Created</td><td><td>Date modified</td><td>Total Posts</td><td>modify</td><td>delete</td></tr>';

	while($row = mysql_fetch_assoc($result)){
	
		$username = $row['username'];
		$domain = $row['domain'];
		$fullName = $username . '@' . $domain;
	
		echo '<tr>';
	
		echo '<td>' . $row['id'] . '</td>';
	
		echo '<td><a href="' . $Config->get('url') . 'admin/user.php?user=' . $fullName . '">' . $fullName . '</a></td>';
	
		echo '<td>' . $row['level'] . "</td><td>" . $row['created'] . "</td><td>" . $row['modified'] . "</td>";
	
		$postCountQuery = mysql_query("SELECT * FROM posts WHERE username = '$username' AND domain = '$domain'");
	
		$postCount = mysql_num_rows($postCountQuery);
	
		echo '<td>' . $postCount . '</td>';
	
		echo '<td>';
		/*
		echo '<form name="modify" action="modifyUser.php">';
		echo '<input type="submit" value="Modify" /></form>';
		*/
		echo '</td>';
	
		echo '<td>';
		/*
		echo '<form name="delete" action="deleteUser.php">';
		echo '<input type="hidden" name="username "value="' . $username . '" />';
		echo '<input type="hidden" name="domain "value="' . $domain . '" />';
		echo '<input type="hidden" name="delete" value="true" />';
		echo '<input type="submit" value="delete" /></form>'; */
		echo '</td>';
	
		echo '</tr>';
	}
		echo '</table>';
	
}
	include('./adminFoot.php');

?>