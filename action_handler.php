<?php

use Campusswap\Util\Config,
        Campusswap\Util\Parser,
        Campusswap\Util\LogUtil,
        Campusswap\Util\Database,
        Campusswap\DAO\PostsDAO,
        Campusswap\DAO\UsersDAO,
        Campusswap\DAO\AuthenticationDAO;

$Parser = new Parser();
$Config = new Config('../etc/config.ini');

$dir = $Config->get('dir');
if(!defined('dir')) { define ('DIR', $dir); }
$url = $Config->get('url');
if(!defined('url')) { define ('URL', $url); }
$enviorment = $Config->get('enviorment');



$database = new Database($Config);
$Conn = $database->connection();
$LogUtil = new LogUtil($Conn, $Config, $Parser);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil, $Parser);
$UserDAO = new UsersDAO($Conn, $Config, $LogUtil, $Parser);
$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $LogUtil, $UsersDAO, $Parser);
$auth = $AuthenticationDAO->getAuthObject();
$error = false;
$image_created = false;
$post_result = false;
define('Megabyte', 1048576);
define('FileUploadLimit', 1.3); //File Upload Limit in MB

//TODO: Filter Post title, price and description but allow the use
// of 1 or 2 Style tags. Probably wont be HTML styling but we will allow some BB style or something


include($dir . 'interface/subpage_head.php');

if(isset($_POST['action']) && $auth->isAdmin()){
    if($_POST['action'] == 'deletePostQuick') {
        
    } else {
        $LogUtil->log('USER'. "action", "incorrect action request - Action REQUEST: " + $_POST['action']);
    }
} else {
    echo 'You do not have permission to be on this page, your IP has been logged';
    $LogUtil->log('IP', 'ACTION', 'Unauthorized Access Post_Item.php', 'unauthorized access');
}
?>

<?php include($dir . 'interface/subpage_foot.php'); ?>