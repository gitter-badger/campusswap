<?php
include('./lib/Config.php');

$config = new Config('./etc/config.ini');


$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');

include($dir . 'lib/Util/Parser.php');

include($dir . 'functions.php');
include($dir . 'lib/Domains.php');
include($dir . 'lib/Users.php');
include($dir . 'lib/vers.php');
include($dir . 'lib/Posts.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/Authentication.php');
include($dir . 'lib/log4php/Logger.php');

$database = new Database();
$conn = $database->connection();

$PostsDAO = new PostsDAO($conn);


if(isset($_GET['college']) && isset($_GET['sort']) && isset($_GET['search']) && isset($_GET['first'])){
    
    $college = $_GET['college'];
    $sort = $_GET['sort'];
    $search = $_GET['search'];
    $first = $_GET['first'];

    $posts = $PostsDAO->getPosts($conn, $search, $college, $sort, $first, $first + 10);

    $switch = "1"; 
    for($x = 0; x < count($posts); $x++){

        include('post.php');
    }
    echo '';
}

?>