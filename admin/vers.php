<?php
include('./adminHead.php');

echo '<center>';

echo '<h1>Verifications</h1>';
$result = mysql_query("SELECT * FROM vers");

echo '<table class="table table-striped table-bordered table-condensed">';

while($row = mysql_fetch_assoc($result)){
	
	echo '<tr>';
	
	echo '<td>' . $row['id'] . "</td><td>" . $row['username'] . "</td><td>" . $row['domain'] . '</td><td>' . $row['type'] . '</td>';
	
	echo '<td>' . $row['ver'] . '</td></tr>';
}
	echo '</table>';
	
	echo '</center>';
	include('./adminFoot.php');

?>