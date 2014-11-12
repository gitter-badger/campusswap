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
		
		Log::logAction('IP', "Created account " . $fullName);
		
		$sql2 = mysql_query("DELETE FROM vers WHERE ver='$key'");

		echo $fullName . ' has successfully created their account with the password: ' . $password;
		mysql_close();
    }
?>

<?php include('./interface/subpage_foot.php'); ?>