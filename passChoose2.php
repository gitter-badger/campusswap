<html>
<head>
<link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<img style="margin-left:42%; text-align:center" src="./img/campusSwapLogoOnly.jpg" />
<div class="container">
<img src="./img/campusSwapLogoOnly.jpg" />
<?php

include('functions.php');



if(isset($_POST['passwordSubmitted'])){
	
		$u = $_POST['username'];
		echo $u . '<br />';
		
		$d = $_POST['domain'];
		echo $d . '<br />';
		
		$key = $_POST['ver'];
		echo $key . '<br />';
		
		$validPass = 'valid';
		
		$p = $_POST['password'];
		echo $p . '<br />';
		echo sha($p) . '<br />';
			
		$sql = mysql_query("INSERT INTO users (id, username, password, domain, level, created, modified, status) VALUES (NULL, '$u', SHA('$p'), '$d', 'normal', NOW(), NOW(), 'fine')");
		
		$fullName = $username . '@' . $domain;
		
		Log::logAction($_SERVER['REMOTE_ADDR'], "Created account " . $fullName);
		
		$sql2 = mysql_query("DELETE FROM vers WHERE ver='$key'");

		echo $fullName . ' has successfully created their account with the password: ' . $password;
		mysql_close();
    }
?>

<?php include('./theme/subpage_foot.php'); ?>