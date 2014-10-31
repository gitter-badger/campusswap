<?php
include('./adminHead.php');

echo '<h2>Logs</h2>';

echo '<table class="table"><tr><td>';

echo '<form action="log.php" method="GET" name="logSearch">';
	echo '<input size="1000" type="text" value="Search Log" width="900px"/>';
echo '</form></td><td>';
echo '<form  class=".form" name="logSearch">';
	echo '<select name="preselected" width="100" OnChange="location.href=domainForm.domain.options[selectedIndex].value">';
		echo '<option value="">Commonly Selected Log Search Terms</option>';
		echo '<option value="?search=liSuccess">logged in successfully</option>';
		echo '<option value="unAuthAccess">unauthorized access</option>';
		echo '<option value="incPass">incorrect password attempt</option>';
		echo '<option value="attemptedBanLogin">attempted banned user login</option>';
		echo '<option value="userContacts">contacted user</option>';
	echo '</select>';
echo '</form></td></tr></table>';


if(isset($_GET['search'])){
echo $_GET['search'] . '<br />';
}
$result = mysql_query("SELECT * FROM log ORDER BY date DESC");

echo '<table class="table table-striped table-bordered table-condensed">';

while($row = mysql_fetch_array($result)){
	
	echo '<tr>';
	
	echo '<td>' . $row['user'] . "</td><td>" . $row['action'] . "</td><td>" . $row['date'] . '</td>';
	
	echo '<td><form style="height:9px;" name="banUser" action="banUser.php" method="post"><input type="submit" value="Ban" /></form></td></tr>';
}

echo '</table>';
	
	
include('./adminFoot.php');

?>