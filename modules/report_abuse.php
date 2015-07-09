<?php
include('../lib/Config.php');

$Config = new Config('../etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/DAO/$UsersDAO.php');
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');

$database = new Database();
$Conn = $database->connection();
$LogUtil = new LogUtil($Conn, $Config);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil);
$UsersDAO = new UsersDAO($Conn, $Config, $LogUtil);

$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $Log, $UsersDAO);
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();
$isLi = $auth->getIsLi();

include(DIR . 'interface/subpage_head.php'); 

if(isset($_POST['abuse'])){

	$post = $_POST['post'];
	$reporter = $_POST['reporter'];
	$abuser = $_POST['abuser'];
        
        $reportedPostUrl = URL . '?item=522';
	
	$LogUtil->log($reporter, 'action', 'report abuse - ' . $reporter . ' reported ' . $abuser . ' - Regarding Item: ' . $post . ' - ' . $reportedPostUrl);
	
	Helper::print_message('You <b>(' . AuthenticationDAO::liFullName() . ')</b> have reported <b>' . $abuser . ' - Regarding Item: <a href="' . $reportedPostUrl . '">' . $post . ' - ' . $reportedPostUrl. '</a>');
}

include(DIR . 'interface/subpage_foot'); 

?>