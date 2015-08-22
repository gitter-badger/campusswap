<?php
use Campusswap\Util\Config,
        Campusswap\Util\LogUtil,
        Campusswap\Util\Parser,
        Campusswap\Util\Database,
        Campusswap\DAO\PostsDAO,
        Campusswap\DAO\UsersDAO,
        Campusswap\DAO\AuthenticationDAO;

$Parser = new Parser();
$Config = new Config('../etc/config.ini');

$dir = $Config->get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = $Config->get('url'); if(!defined('url')) { define ('URL', $url); }


$debug = $Parser->isTrue($Config->get('debug'));
//Also add timer

$database = new Database($Config);
$Conn = $database->connection();

$LogUtil = new LogUtil($Conn, $Config, $Parser);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil);
$UsersDAO = new UsersDAO($Conn, $Config, $LogUtil);

$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $Log, $UsersDAO, $Parser);
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