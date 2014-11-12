<?php


include('./lib/Config.php');

$config = new Config('./etc/config.ini');

$dir = Config::get('dir');
if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url');
if(!defined('url')) { define ('URL', $url); }

include $dir . 'lib/Util/Helper.php';
include $dir . 'lib/Util/Parser.php';

include(DIR . 'interface/subpage_head.php');


unset($_SESSION['user']);
unset($_SESSION['domain']);
unset($_SESSION['userId']);
unset($_SESSION['level']);

session_destroy();
session_unset();

?>
<br />

    <div class="alert alert-success">You have successfully logged out!</div>
     <?php Helper::return_home_button(); ?>

<?php include('./interface/subpage_foot.php'); ?>