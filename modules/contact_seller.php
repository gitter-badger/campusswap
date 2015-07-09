<?php

include('../lib/Config.php');

$Config = new Config('../etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/DAO/DomainsDAO.php');
include($dir . 'lib/DAO/UsersDAO.php');

include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Util/LogUtil.php');

include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');

$debug = Parser::isTrue(Config::get('debug'));
//Also add timer

$database = new Database();
$Conn = $database->connection();

$LogUtil = new LogUtil($Conn, $Config);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil);
$UsersDAO = new UsersDAO($Conn, $Config, $LogUtil);

$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $Log, $UsersDAO);
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$isLi = $auth->getIsLi();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();

$simple = true;
include $dir . 'interface/subpage_head.php';
?>

<?php
if(isset($_GET['approach']) && $isLi){

    $approach = Parser::sanitize($_GET['approach']);
	$seller_email = Parser::sanitize($_GET['sellerEmail']);
	$post_id = Parser::sanitize($_GET['id']);

    $seller = $UsersDAO->getUserFromId($post_id);
    $items = $PostsDAO->getPost($post_id);
    $item = $items[0];

    echo '<h1 class="center">Contact User</h1>';

    echo '<div class="alert alert-success">To: ' . Helper::obfuscate_username($seller_email) . '</div>';
	echo '<div class="alert alert-success">From: ' . $liUser . '@' . $liDomain . '</div>';
	
	echo '<div class="alert alert-info">Item: ' . $item->getitem() . '</div><br />';
	
	echo '<form action="' . URL . 'modules/contact_seller.php" method="post">';
	
	echo '<textarea class="largeTextArea" name="contents" rows="6" cols="37">Write message here</textarea><br />';
	
	echo '<input type="hidden" name="send_email" value="' . $seller_email . '">';
	echo '<input type="hidden" name="id" value="' . $post_id . '">';

    echo '<input class="btn btn-primary btn-md" type="submit" value="Contact User!" />';
    Helper::close_lightwindow_button();
} else if(isset($_POST['send_email']) && AuthenticationDAO::isLi()){
    $email = $_POST['send_email'];
	$id = $_POST['id'];
    $contents = Parser::sanitize($_POST['contents']);

    $items = $PostsDAO->getPost($id);
    $item = $items[0];

    $LogUtil->log($liFullName, "action", "user contact - " . $liFullName . " CONTACTED " . $email . " MESSAGE:  " . $contents);

    $email_content = '<html><head>CampusSwap.Net</head></head><body><h1>Campus Swap</h1><h2>You have been contacted by <b>' . $liFullName . '</b></h2>';
    $email_content .= '<p>Message regarding item: <b>"' . $item->getItem() . '"</b></p>';
    $email_content .= '<p>' . $contents . '</p>';
    $email_content .= '<p>You may reply to this user at <b>' . $liFullName . '</b></p>';
    $email_content .= '<p>-<b>The Campus Swap Team</b></p></body></html>';

	echo '<div class="alert alert-success">You have contacted the user about ' . $item->getItem() . ', he can reply to ' . $liFullName . ' at his convenience</div>';

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: do_not_reply@campusswap.net' . "\r\n" . 'Reply-To: ' . $liFullName;
	
	$subject = 'You have been contacted on CampusSwap.net about ' . $item->getItem() . '!';
	
	mail($email, $subject, $email_content, $headers);

    Helper::close_lightwindow_button();
} else {
    echo '<div class="alert alert-danger">You do not have persmission to be on this page, your ip ' . LogUtil::getIp() . ' has been logged, and it may be banned.</div>';
    $LogUtil->log("IP", "action", "unauthorized access to modify_item.php");

}

?>

</body>
</html>
