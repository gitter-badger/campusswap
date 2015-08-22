<html>
<head>
<link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<img style="margin-left:42%; text-align:center" src="./interface/img/logo_txt_only.jpg" />
<div style="text-align:center;background-color:#d0ddcf;border: 1px solid #9CAA9C;width:300px;margin-left:30%;width:600px">


<?php

include('functions.php');



if(isset($_POST['delete']) && $_POST['delete']=='yes'){
	$id = $AuthenticationDAO->getLiId();
	mysql_query("DELETE FROM users WHERE id = '$id'");
	unset($_SESSION['user']);
	unset($_SESSION['domain']);
	unset($_SESSION['userId']);
	unset($_SESSION['level']);
	session_destroy();
	session_unset();
	
	echo '<center>Thank you for using College Hustler</center>';
} else {
	echo '<form action="deleteAccount.php" method="post" />';
	echo 'Are you sure you want to delete your account? You will <b>LOSE ALL YOUR POSTS, THEY ARE UNRECOVERABLE!</b>';
	echo '<input type="submit" value="Yes, delete my account and all my posts" />';
	echo '<input type="hidden" value="yes" name="delete" />';
	echo '<a href="' . $Config->get('url') . '">No do not delete it</a>';
	echo '</form>';
}

?>


</div>

<a style="margin-left:48%;text-align:center;color:black" href="<?= $Config->get('url'); ?>">Return Home</a>

</html>