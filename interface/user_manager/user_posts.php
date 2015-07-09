<?php

//TODO: htaccess

include('../../lib/Config.php');

$Config = new Config('../../etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }
$debug = Parser::isTrue(Config::get('debug'));

include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');
include($dir . 'lib/DAO/UsersDAO.php');
include($dir . 'lib/DAO/UsersDAO.php');

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

$simple = true; // TODO: This looks  - Define what this does + refactor global usage if needed
$subpage = false; // TODO: This looks retarded also - its a variable set in the debug panel module
include $dir . 'interface/subpage_head.php';

if(AuthenticationDAO::isLi()){
    $posts = $PostsDAO->getPostsUser($liUser, $liDomain);
    echo 'sql - ' . $PostsDAO::$sql;
    echo 'count - ' . $PostsDAO::$fp_count;
?>
    <table border="0" cellspacing="0" cellpadding="0" style=";font-size:1em;">
        <div style="padding-left:2px;border-bottom:1px white solid"><h1><?= AuthenticationDAO::liUser() ?>@<?= AuthenticationDAO::liDomain() ?> Posts</h1>
            &nbsp;
            <a class="btn btn-default btn-sm" href="<?= URL ?>interface/contact.php?contact=bug" target="_top">Report a bug</a>
            &nbsp;
            <a class="btn btn-default btn-sm" href="<?=  URL ?>interface/disclaimer.php" target="_top">Disclaimer</a>
            &nbsp;
            <a class="btn btn-default btn-sm" href="<?=  URL ?>interface/contact.php" target="_top">Contact</a>
            &nbsp;
            <a class="btn btn-default btn-sm" href="<?=  URL ?>interface/deleteAccount.php" target="_top">Delete Account</a>
        </div>
    <?php
    echo 'Posts: ' . count($posts);
    for($x = 0; $x < count($posts); $x++){
        $rowDescription = null;
        $rowDescription = substr($posts[$x]->getDescription(), 0, 180) . "...";
        $rowItem = substr($posts[$x]->getItem(), 0, 20);

        date_default_timezone_set('America/Denver'); //TODO: Implement this dated posted below
        $deleted = new DateTime($posts[$x]->getCreated());
        $todaysDate = date('d.m.y');
        $today = new dateTime($todaysDate);
        $deleted->modify("+60 day");

        $interval = $today->diff($deleted);

        echo '<tr>';
            echo '<td><h4>' . $rowItem . '</td><td><small>&nbsp;&nbsp;(' . $interval->format('%R%a') . ' days left)</small></h4>&nbsp;</td><td>&nbsp;' . $rowDescription . '</td>';

            echo '<td style="padding-top:5px">';

            echo '<form target="_top" name="editItem" action="' . URL . 'interface/user_manager/modify_item.php" method="post">';

            echo '<input type="hidden" value="' . $posts[$x]->getId() . '" name="id">';
            echo '<input type="hidden" name="edit" value="true">';

            echo '&nbsp;&nbsp;<input  class="btn btn-warning" type="submit" value="Edit" />&nbsp;&nbsp;';

            echo '</form></td>';

            echo '<td style="padding-top:5px"> <form target="_top" name="deleteItem" action="' . URL . 'interface/user_manager/modify_item.php" method="post">';

            echo '<input type="hidden" value="' . $posts[$x]->getId() . '" name="id">';

            echo '<input type="hidden" name="delete" value="true">';
            echo '<input class="btn btn-danger" target="_top" type="submit" value="Delete" />';
            echo '</form></td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<div class="alert alert-danger">You not logged in so you may not view this page. If you keep doing this you may get banned.</div>';
    $LogUtil->log('IP', 'ACTION', 'Attempted to view user_posts.php without authentication', 'unauthorized access');

}


include $dir . 'interface/subpage_foot.php';

?>

