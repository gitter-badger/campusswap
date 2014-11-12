<?php
include('./lib/Config.php');
$config = new Config('./etc/config.ini');
$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/Util/Timer.php');
$Timer = new Timer(null);
$Timer->start();

include($dir . 'lib/DAO/AuthenticationDAO.php');
include($dir . 'lib/DAO/PostsDAO.php');

include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Util/Helper.php');


include($dir . 'lib/Database.php');


$database = new Database();
$conn = $database->connection();
$LogUtil = new LogUtil($conn, $config);
$PostsDAO = new PostsDAO($conn, $config, $LogUtil);

if(isset($_GET['college']) && isset($_GET['sort']) && isset($_GET['search']) && isset($_GET['first'])){
    
    $college = Parser::getString($_GET['college']);
    $sort = Parser::getString($_GET['sort']);
    $search = Parser::getString($_GET['search']);
    $first = $_GET['first'];

    $posts = $PostsDAO->getPosts($college, $search, $sort, $first, 10);

    if(Parser::isTrue(Config::get('debug'))) {
        echo '<div style="margin:10px; color:black;border:1px solid black; padding:10px; text-align:center; width:100%"><B>SQL: </B>' . PostsDAO::$limit_sql . '</div>';
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
$LogUtil->log(AuthenticationDAO::liFullName(), "info", "Scroll Timer: " . Timer::$last_time);
?>
<script type="text/javascript"> console.log(<?= Timer::$last_time ?>); </script>
