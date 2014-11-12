<?php
include('../../lib/Config.php');

$config = new Config('../../etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');

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
?>

<?php include(DIR . 'interface/subpage_head.php'); ?>

<?php

if(isset($_POST['abuse'])){

	$post = $_POST['post'];
	$reporter = $_POST['reporter'];
	$abuser = $_POST['abuser'];
	
	
	Log::logAction($abuser, ' has been reported for abuse about ' . $post . ' by ' . $reporter);
	
	echo $abuser . ' has been reported for abuse about <a href="' . $post . '">http://localhost:8888/campusswap/?item=90</a> by ' . $reporter;
}

?>


</div>

<a style="margin-left:48%;text-align:center;color:black" href="<?= Config::get('url'); ?>">Return Home</a>

</html>