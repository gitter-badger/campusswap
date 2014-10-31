
<?php
include('adminHead.php');

echo '<h1>Welcome Admin</h1>';

echo '<form action="makePost.php" method="GET" />';
echo '<input type="text" value="count" name="count" />';
echo '<input type="submit" value="submit" />';

	
	
include('adminFoot.php');



?>