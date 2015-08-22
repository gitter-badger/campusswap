<?php

use Campusswap\Util\Config,
        Campusswap\Util\Timer,
        Campusswap\Util\Parser,
        Campusswap\Util\LogUtil,
        Campusswap\Util\Database,
        Campusswap\DAO\DomainsDAO,
        Campusswap\DAO\UsersDAO,
        Campusswap\DAO\PostsDAO;

require_once __DIR__ . "/vendor/autoload.php";

session_start();
$Parser = new Parser();
$Config = new Config('./etc/config.ini');

//TODO: Before final deployment, replace includes with requires, to prevent 

// Variables
$dir = $Config->get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = $Config->get('url'); if(!defined('url')) { define ('URL', $url); }
$version = $Config->get('version');
$enviorment = $Config->get('enviorment');
$debug = $Parser->isTrue($Config->get('debug'));
$debug_location = $Parser->isTrue($Config->get('debug_location'));

// DB
try {
    $database = new Database($Config);
    $Conn = $database->connection();
} catch (Exception $ex) {
    $Helper->print_error("Could not establish a database connection");
    //todo: make a simple logger by overloading the LogUtil constructor to run w/out a db connection to log this error
    die('Could not establish a DB Connection');
}

// DAO's and Log util
$Log = new LogUtil($Conn, $Config, $Parser);
$PostsDAO = new PostsDAO($Conn, $Config, $Log);
$DomainsDAO = new DomainsDAO($Conn, $Config, $Log);
$UsersDAO = new UsersDAO($Conn, $Config, $Log);

if(isset($_GET['college']) && isset($_GET['sort']) && isset($_GET['search']) && isset($_GET['first'])){
    
    $college = $Parser->getBoolean($_GET['college']);
    $sort = $Parser->getBoolean($_GET['sort']);
    $search = $Parser->getBoolean($_GET['search']);
    $first = $_GET['first'];

    $posts = $PostsDAO->getPosts($college, $search, $sort, $first, 10);

    if($Parser->isTrue($Config->get('debug'))) {
        echo '<div style="margin:10px; color:black;border:1px solid black; padding:10px; text-align:center; width:100%"><B>SQL: </B>' . $PostsDAO->limit_sql . '</div>';
    }

    $switch = "1"; 
    for($x = 0; $x < count($posts); $x++){
        include($dir . 'modules/post.php');
    }
    echo '';
} else {
    echo '<b>Not all the AJAX Parameters were set</b>';
}

$Timer->stop();
$LogUtil->log('USER'. "info", "Scroll Timer: " . Timer::$last_time);
?>
<script type="text/javascript"> console.log(<?= Timer::$last_time ?>); </script>
