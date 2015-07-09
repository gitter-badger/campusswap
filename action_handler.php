<?php

include('../lib/Config.php');

$Config = new Config('../etc/config.ini');

$dir = Config::get('dir');
if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url');
if(!defined('url')) { define ('URL', $url); }
$enviorment = Config::get('enviorment');

include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');

$database = new Database();
$Conn = $database->connection();
$LogUtil = new LogUtil($Conn, $Config);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil);

$error = false;
$image_created = false;
$post_result = false;
define('Megabyte', 1048576);
define('FileUploadLimit', 1.3); //File Upload Limit in MB

//TODO: Filter Post title, price and description but allow the use
// of 1 or 2 Style tags. Probably wont be HTML styling but we will allow some BB style or something


include($dir . 'interface/subpage_head.php');

if(isset($_POST['action']) && AuthenticationDAO::isAdmin()){
    if($_POST['action'] == 'deletePostQuick') {
        
    } else {
        $LogUtil->log(AuthenticationDAO::liFullName(), "action", "incorrect action request - Action REQUEST: " + $_POST['action']);
    }
} else {
    echo 'You do not have permission to be on this page, your IP has been logged';
    $LogUtil->log('IP', 'ACTION', 'Unauthorized Access Post_Item.php', 'unauthorized access');
}
?>

<?php include($dir . 'interface/subpage_foot.php'); ?>