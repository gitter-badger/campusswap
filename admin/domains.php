<?php
include('./adminHead.php');

echo '<center>';

if(isset($_POST['add'])){
	$domainPost = $_POST['domain'];
	$name = $_POST['name'];
	
	$result= mysql_query("INSERT INTO domains (name, domain) VALUES ('$name', '$domainPost')");
	
	echo $domainPost . ' ' . $name . ' has been added';
	
} else if(isset($_POST['delete'])) {
	
	$id = $_POST['id'];
	
	$result = mysql_query("SELECT name FROM domains WHERE id = '$id'");
	
	$row = mysql_fetch_row($result);
	
	echo '<br />Confirm delete domain ' . $row[0];
	
	echo '<form name="add" action="./domains.php" method="post">';
	echo '<input type="submit" value="Confirm delete" />';
	echo '<input type="hidden" name="id" value="' . $id . '" />';
	echo '<input type="hidden" name="delete2" value="TRUE" />';
	echo '</form>';
	
	
	
} else if(isset($_POST['delete2'])){
	$id = $_POST['id'];
	
	echo 'deleted';
	
	$result = mysql_query("DELETE FROM domains WHERE id = '$id'");
}
	echo '<h1>Domains</h1>';
	
	echo '<form name="add" action="./domains.php" method="post">';
	echo '<input type="text" name="domain" value="example.edu" />';
	echo '<input type="text" name="name" value="Example University" />';
	echo '<input type="hidden" name="add" value="add" />';
	echo '<input type="submit" value="Submit" />';
	echo '</form>';
	
	$result = mysql_query("SELECT * FROM domains");

	echo '<table class="table table-striped table-bordered table-condensed">';

	while($row = mysql_fetch_assoc($result)){

		echo '<tr>';

		echo '<td>' . $row['id'] . "</td><td>" . $row['domain'] . "</td><td>" . $row['name'] . '</td>';
		
		echo '<td><form style="height:10px;" action="domains.php" method="post">';
		echo '<input type="submit" value="Delete" />';
		echo '<input type="hidden" name="id" value="' . $row['id'] . '" />';
		echo '<input type="hidden" name="delete" value="delete" />';
		echo '</form>';

		echo '</tr>';
	}
	
	echo '</table>';

	echo '</center>';

	include('./adminFoot.php');

?>