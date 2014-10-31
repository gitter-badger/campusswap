<?php 


//session_start();
        
include('./lib/Config.php');

$config = new Config('./etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }
$version = Config::get('version');

include($dir . 'lib/DAO/PostsDAO.php');

include($dir . 'lib/Util/Parser.php');

include($dir . 'lib/Helper.php');
include($dir . 'lib/Domains.php');
include($dir . 'lib/Users.php');
include($dir . 'lib/vers.php');
include($dir . 'lib/Posts.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/Authentication.php');
include($dir . 'lib/log4php/Logger.php');
include($dir . 'lib/Mobile_Detect.php');

$debug = Parser::isTrue(Config::get('debug'));

Logger::configure($dir . 'etc/log4php.xml'); //TODO: Production has different config file
$log = Logger::getLogger("main");
//Also add timer

$liUser = Authentication::liUser();
$liDomain = Authentication::liDomain();
$liId = Authentication::liId();
$liLevel = Authentication::liLevel();

$database = new Database();
$conn = $database->connection();

$PostsDAO = New PostsDAO($conn);

$domains = new Domains($conn);
$domains->fetchColleges();

$thisUser = new Users($liUser, $conn);

if(isset($_SESSION['user'])){ //TODO: Lock this down, this is insecure and shitty
    $loggedIn = TRUE;
} else {
    $loggedIn = FALSE;
}

/* @var $_SERVER callable */
$ip = $_SERVER['REMOTE_ADDR'];

date_default_timezone_set('America/New_York');
$today = date("y-m-d");

include DIR . 'modules/disect_url.php';

if($item){
    $log->info("We are loading a single post: " . $id);
    $PostsDAO->getPost($id);
    $pages = 0;
    $post_count = 1;
} else {
    //getPosts($conn, $search = false, $college = 'all', $sort = false, $first_limit = false,  $second_limit = false, $debug = false){
    $posts = $PostsDAO->getPosts($college, $search, $sort, 0, 40);

    $log->info("We are loading multiple (" . $PostsDAO::$row_count . ") posts: " . $PostsDAO::$sql);

    // Choose AJAX Infinite Scrolls
    $pages = 0;
    if($posts != false){
        $post_count = count($posts);
        $pages = ceil((($post_count - 40) / 10));
    } else {
        $post_count = false;
        $pages = false;
    }
}

if($debug){
    include DIR . 'theme/debug.php';
}

include('./theme/head.php');

include('./modules/analytics.php');

if(Authentication::isLi()){
    include('./theme/index/post_item.php');
} else {
    if(isset($_COOKIE['welcome'])){ } else {
        include('./theme/index/welcome_box.php');
    }
}

include('./theme/index/query_selector.php');

echo '<h4>' . $title . '</h4>'; //CURRENT QUERY TITLE ECHO
echo '<div id="postswrapper">';

//THE LOOP
if($post_count<1  && !$post_count){
    if($search){
        echo '<center><b>Sorry, we could not find anything in that matched your search <i>' . $search . '</i></b></center>';
    } else {
        echo '<center><b> Sorry we could not find any posts from ' . Domains::getCollegeName($college) . ', try <a href="' . Config::get('url') . '">from the beginning</a></b></center>';
    }
} else {

    $switch = 1;
    $count = 0;

    for($x = 0; $x < $post_count; $x++){
        include(DIR . 'modules/post.php');

        if($count <9){
            $count++;
        } else {
            $count = 0;
        }
    }
}

echo '</div><div id="loadmoreajaxloader" style="display:none;"><center><img src="./img/ajax-loader.gif" /></center></div>';

include(DIR . 'theme/foot.php');

include(DIR . 'modules/analytics.php');
 ?>
