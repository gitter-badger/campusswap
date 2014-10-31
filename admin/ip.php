<?php
include('./adminHead.php');

echo '<center>';

echo '<h1>IP Addresses</h1>';

echo '<form name="add" action="ip.php" method="post">';
echo '</form>';
$result = mysql_query("SELECT * FROM ipLog");

echo '<table width="600" border="1">';

echo '<tr><td>id</td><td>ip</td><td>usernames</td><td>status</td><td>visits</td><td>failed_logons</td><td>date accessed</td></tr>';

while($row = mysql_fetch_assoc($result)){
	
	echo '<tr>';
	
	echo '<td>' . $row['id'] . "</td> <td>" . $row['ip'] . "</td> <td>" . $row['usernames'] . '</td>';
	echo '<td>' . $row['status'] . '</td><td>' . $row['visits'] . '</td><td>' . $row['failed_logons'] . '</td><td>' . $row['date'] . '</td>';
	echo '</tr>';
}
	echo '</table>';
	
	echo '</center>';
	include('./adminFoot.php');

?>