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

$database = new Database($Config);
$Conn = $database->connection();
$log = new LogUtil($Conn, $Config, $Parser);
$PostsDAO = new PostsDAO($Conn, $Config, $log);
$UsersDAO = new UsersDAO($Conn, $Config, $log);

$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $Log, $UsersDAO, $Parser);
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$isLi = $auth->getIsLi();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();

$this_user = $UsersDAO->getUserFromId($AuthenticationDAO->getLiId());

if(isset($_GET['id']) && $AuthenticationDAO->isLi()){

    $item_id = $_GET['id'];

    if(!$this_user->doesUserLike($item_id)){

        $result = $PostsDAO->likeItem($this_user->getId(), $item_id);

        if($Parser->isFalse($result)){
            $log->error($this_user->getFullName(), $this_user->getFullName() . " Error liking post ID:" . $item_id);
        } else {
            echo 'It worked';
        }

    } else {
        echo 'You already like this item';
    }
}

?>