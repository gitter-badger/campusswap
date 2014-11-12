<?php

include('../lib/Config.php');

$config = new Config('../etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');
include $dir . 'lib/DAO/UsersDAO.php';
include($dir . 'lib/DAO/AuthenticationDAO.php');

include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');

include($dir . 'lib/Database.php');

$debug = Parser::isTrue(Config::get('debug'));

$database = new Database();
$conn = $database->connection();
$LogUtil = new LogUtil($conn, $config);
$PostsDAO = new PostsDAO($conn, $config, $LogUtil);
$UsersDAO = new UsersDAO($conn, $config, $LogUtil);

$this_user = $UsersDAO->getUserFromId(AuthenticationDAO::liId());

if(isset($_GET['id']) && AuthenticationDAO::isLi()){

    $id = $_GET['id'];

    if(Parser::isFalse($this_user->doesUserLike($_GET['id']))){

        $result = $PostsDAO->likeItem(AuthenticationDAO::liUser(), $id);

        if(Parser::isFalse($result)){
            $log->error(AuthenticationDAO::liUser() . "@" . AuthenticationDAO::liDomain() . " Error liking post ID:" . $id);
        }

    }
}

?>