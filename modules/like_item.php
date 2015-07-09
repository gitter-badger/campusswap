<?php

include('../lib/Config.php');

$Config = new Config('../etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');
include $dir . 'lib/DAO/UsersDAO.php';
include($dir . 'lib/DAO/AuthenticationDAO.php');

include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');

include($dir . 'lib/Database.php');

$debug = Parser::isTrue(Config::get('debug'));

$database = new Database();
$Conn = $database->connection();
$log = new LogUtil($Conn, $Config);
$PostsDAO = new PostsDAO($Conn, $Config, $log);
$UsersDAO = new UsersDAO($Conn, $Config, $log);

$this_user = $UsersDAO->getUserFromId(AuthenticationDAO::liId());

if(isset($_GET['id']) && AuthenticationDAO::isLi()){

    $item_id = $_GET['id'];

    if(!$this_user->doesUserLike($item_id)){

        $result = $PostsDAO->likeItem($this_user->getId(), $item_id);

        if(Parser::isFalse($result)){
            $log->error($this_user->getFullName(), $this_user->getFullName() . " Error liking post ID:" . $item_id);
        } else {
            echo 'It worked';
        }

    } else {
        echo 'You already like this item';
    }
}

?>