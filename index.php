<?php 
//session_start();
        
include('./lib/Config.php');

$config = new Config('./etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }
$version = Config::get('version');

//TODO: Before final deployment, replace includes with requires, to prevent errors in production
include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/DAO/DomainsDAO.php');
include($dir . 'lib/DAO/UsersDAO.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');

include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Util/Mobile_Detect.php');
include($dir . 'lib/Util/Timer.php');

include($dir . 'lib/Database.php');

$debug = Parser::isTrue(Config::get('debug'));

$AuthenticationDAO = new AuthenticationDAO($config);
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();
$isLi = $auth->getIsLi();

$database = new Database();
$conn = $database->connection();
$log = new LogUtil($conn, $config);
$PostsDAO = new PostsDAO($conn, $config, $log);
$DomainsDAO = new DomainsDAO($conn, $config, $log);
$UsersDAO = new UsersDAO($conn, $config, $log);

$Timer = new Timer($log);
$Times = Array();

$this_user = $UsersDAO->getUserFromId($liId);
$all_domains = $DomainsDAO->getAllDomains();

if(isset($_SESSION['user'])){ //TODO: Lock this down, this is insecure and shitty
    $loggedIn = TRUE;
} else {
    $loggedIn = FALSE;
}

/* @var $_SERVER callable */
$ip = LogUtil::getIp();

date_default_timezone_set('America/New_York');
$today = date("y-m-d");

include DIR . 'modules/disect_url.php';

if($item){
    $log->log($liFullName, "info", "We are loading a Single post: " . $id);
    $posts[0] = $PostsDAO->getPost($id);
    $pages = 0;
    $total_count = 1;
} else {
    $Timer->start();
    $posts = $PostsDAO->getPosts($college, $search, $sort, 0, 40);
    $Timer->stop();
    $log->log($liFullName, 'info', 'Timer: PostsDAO->getPosts() took ' . Timer::$last_time);

    $total_count = $PostsDAO->getTotalRows($PostsDAO::$sql);

    $log->log($liFullName, "info", "We are loading multiple (" . PostsDAO::$total_count . " posts total) (" . PostsDAO::$fp_count .  " posts on front-page) SQL: " . $PostsDAO::$sql);

    // Choose AJAX Infinite Scrolls
    $pages = 0;
    if($posts != false){
        $pages = ceil((($total_count - 40) / 10));
    } else {
        $total_count = false;
        $pages = false;
    }
}

if($debug){
    include DIR . 'modules/debug.php';
}

include(DIR . 'interface/head.php');

include(DIR . 'modules/analytics.php');

if(AuthenticationDAO::isLi()){
    include(DIR . 'interface/index/post_item.php');
} else {
    if(isset($_COOKIE['welcome'])){ } else {
        include(DIR . 'interface/index/welcome_box.php');
    }
}

include(DIR . 'interface/index/query_selector.php');

echo '<h4>' . $title . '</h4>'; //CURRENT QUERY TITLE ECHO
echo '<div id="postswrapper">';

//THE LOOP
$count = 0;
$switch = 1;
if(PostsDAO::$fp_count<1  && !PostsDAO::$fp_count) {
    if ($search) {
        echo '<b>Sorry, we could not find anything in that matched your search <i>' . $search . '</i></b><';
        $log->log($liFullName, "action", $liFullName . " searched for " . $search, "no results found");
    } else {
        echo '<b> Sorry we could not find any posts from ' . DomainsDAO::getCollegeName($college) . ', try <a href="' . Config::get('url') . '">from the beginning</a></b>';
        $log->log($liFullName, "action", $liFullName . " looked up " . $college, "no results found");
    }
} else if(PostsDAO::$fp_count == 1) {
    $x = 0;
    include(DIR . 'modules/post.php'); //We can probably remove this now because getPost returns an array anyways
} else {
    for($x = 0; $x < PostsDAO::$fp_count; $x++){
        include(DIR . 'modules/post.php');

        if($count <9){
            $count++;
        } else {
            $count = 0;
        }
    }
}

echo '</div><div id="loadmoreajaxloader" style="display:none;"><center><img src="' . URL . 'interface/img/ajax-loader.gif" /></center></div>';

include(DIR . 'interface/foot.php');

include(DIR . 'modules/analytics.php');
 ?>
