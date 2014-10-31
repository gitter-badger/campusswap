<?php
include('./adminHead.php');
echo '<center>';


if(isset($_POST['submitted'])){
	
	$u1 = $_POST['username1'];
	$d1 = $_POST['domain1'];
	$p1 = $_POST['password1'];
	
	mysql_query("INSERT INTO users (id, username, password, domain, level, created, modified) VALUES (NULL, '$u1', SHA('$p1'), '$d1', 'normal', NOW(), NOW())");
	
	echo $u1 . '@' . $d1 . ' added <br />';
	
	if($_POST['use2']=='yes'){
		
		$u2 = $_POST['username2'];
		$d2 = $_POST['domain2'];
		$p2 = $_POST['password2'];

		mysql_query("INSERT INTO users (id, username, password, domain, level, created, modified) VALUES (NULL, '$u2', SHA('$p2'), '$d2', 'normal', NOW(), NOW())");
		
		echo $u2 . '@' . $d2 . ' added <br />';
	}
	
	if($_POST['use3']=='yes'){
		
		$u3 = $_POST['username3'];
		$d3 = $_POST['domain3'];
		$p3 = $_POST['password3'];

		mysql_query("INSERT INTO users (id, username, password, domain, level, created, modified) VALUES (NULL, '$u3', SHA('$p3'), '$d3', 'normal', NOW(), NOW())");
		
		echo $u3 . '@' . $d3 . ' added <br />';
	}
	
	if($_POST['use4']=='yes'){
		
		$u4 = $_POST['username4'];
		$d4 = $_POST['domain4'];
		$p4 = $_POST['password4'];

		mysql_query("INSERT INTO users (id, username, password, domain, level, created, modified) VALUES (NULL, '$u4', SHA('$p4'), '$d4', 'normal', NOW(), NOW())");
		
		echo $u4 . '@' . $d4 . ' added <br />';
	}
	
	if($_POST['use5']=='yes'){
		
		$u5 = $_POST['username5'];
		$d5 = $_POST['domain5'];
		$p5 = $_POST['password5'];

		mysql_query("INSERT INTO users (id, username, password, domain, level, created, modified) VALUES (NULL, '$u5', SHA('$p5'), '$d5', 'normal', NOW(), NOW())");
		
		echo $u5 . '@' . $d5 . ' added <br />';
	}
	
} else {

echo '<h1>Add 5 Users at a time</h1>'; ?>

<form action="./addUsers.php" method="POST">

<table>
	<tr>
		<td></td>
		<td>Username</td>
		<td>Domain</td>
		<td>password</td>
	</tr>
	<tr>
		<td>Required</td>
		<td><input type="text" name="username1" /></td>
		<td><input type="text" name="domain1" /></td>
		<td><input type="text" name="password1" /></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="use2" value="yes"/></td>
		<td><input type="text" name="username2" /></td>
		<td><input type="text" name="domain2" /></td>
		<td><input type="text" name="password2" /></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="use3" value="yes" /></td>
		<td><input type="text" name="username3" /></td>
		<td><input type="text" name="domain3" /></td>
		<td><input type="text" name="password3" /></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="use4" value="yes" /></td>
		<td><input type="text" name="username4" /></td>
		<td><input type="text" name="domain4" /></td>
		<td><input type="text" name="password4" /></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="use5" value="yes" /></td>
		<td><input type="text" name="username5" /></td>
		<td><input type="text" name="domain5" /></td>
		<td><input type="text" name="password5" /></td>
	</tr>
</table>
	<input type="hidden" name="submitted">
	<input type="submit" value="submit" />
</form>



<?php
}

include('./adminFoot.php');

?>