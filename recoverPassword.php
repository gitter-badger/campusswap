<?php

include('../../lib/Config.php');

$config = new Config('../../etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');

include($dir . 'lib/Util/Parser.php');

include($dir . 'lib/Util/Helper.php');


include($dir . 'lib/vers.php');

include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');
include($dir . 'lib/Util/LogUtil.php');

$debug = Parser::isTrue(Config::get('debug'));

$AuthenticationDAO = new AuthenticationDAO($config);
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();
$isLi = $auth->getIsLi();

$database = new Database();
$conn = $database->connection();

$LogUtil = new LogUtil($conn, $config);
$PostsDAO = new PostsDAO($conn, $config, $LogUtil);

include(DIR . 'interface/subpage_head.php');

//TODO: Finish Password Recovery

if(isset($_POST['recover'])) {
	
	$u = $_POST['user'];
	$d = $_POST['domain'];
	$fullName = $u . '@' . $d;
	echo 'An email has been dispatched to ' . $fullName . '<br />';
	$key = md5(uniqid(rand(), true));
	
	$query = mysql_query("SELECT * FROM vers WHERE username = '$u' AND domain = '$d'");
	if(mysql_num_rows($query) > 0){
		mysql_query("UPDATE vers SET ver = '$key' WHERE username = '$u' AND domain = '$d'");
	} else {
		mysql_query("INSERT INTO vers (ver, username, domain, type) VALUES ('$key', '$u', '$d', 'recover')");
	}
	
	
	//echo '<a href="' . Config::get('url') . 'recoverPassword.php?key=' . $key . '">' . $key . '</a><br />';
	
	$email = $fullName;
	$subject = 'CampusSwap.net Password Recovery';
	$content = 'CampusSwap.net - to recover your password, follow this link' . Config::get('url') . 'recoverPassword.php?key=' . $key;
	
	mail($email, $subject, $content);

} else if(isset($_GET['key'])){

	$key = $_GET['key'];
	
	$query = mysql_query("SELECT username, domain FROM vers WHERE ver = '$key'");
	
	if(mysql_num_rows($query) == 1){
		
		echo 'Choose a new password';
		
		$array = mysql_fetch_array($query);
		echo '<form action="recoverPassword.php" method="post">';
		echo '<input type="password" name="password" />';
		echo '<input type="hidden" name="username" value="' . $array[0] . '" />';
		echo '<input type="hidden" name="domain" value="' . $array[1] . '" />';
		echo '<input type="hidden" name="key" value="' . $_GET['key'] . '" />';
		echo '<input type="hidden" name="resetPass" value="TRUE" />';
		echo '<input type="submit" value="recover" />';
	}

} else if(isset($_POST['resetPass'])){
	$p = $_POST['password'];
	$u = $_POST['username'];
	$d = $_POST['domain'];
	$k = $_POST['key'];
	
	mysql_query("UPDATE users SET password = SHA('$p') WHERE username='$u' AND domain='$d'");
	
	mysql_query("DELETE FROM vers WHERE ver = '$k'");
	
	echo 'Password has been changed';
} else {
	echo 'If you have lost your password, you can recover it by having an email dispatched with a reset password link<br />';
	echo '<form action="recoverPassword.php" method="post">';
	echo '<input type="text" name="user" value="username" />';
	echo '<select name="domain">';

	$domain_result = mysql_query("SELECT domain, name FROM domains");

	while($row = mysql_fetch_assoc($domain_result)){
		echo '<option value = "' . $row['domain'] . '">@' . $row['domain'] . '</option>';
	}

	echo '</select><br />';
	echo '<input type="hidden" name="recover" value="true">';
	echo '<input type="submit" value="Recover Password"/>';
	echo '</form>';
}

?>
</div>

<a style="margin-top:20px;margin-left:48%;text-align:center;color:black" href="<?= Config::get('url'); ?>">Return Home</a>

</html>