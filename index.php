<?php
//Task - Create OCEF - Open College Ecommerce Framework with PhpIng build tool

use Campusswap\Util\Config,
        Campusswap\Util\Timer,
        Campusswap\Util\Parser,
        Campusswap\Util\LogUtil,
        Campusswap\Util\Database,
        Campusswap\DAO\DomainsDAO,
        Campusswap\DAO\UsersDAO,
        Campusswap\DAO\PostsDAO,
        Campusswap\DAO\AccessDAO,
        Campusswap\DAO\AuthenticationDAO;

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
$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $Log, $UsersDAO, $Parser);
$AccessDAO = new AccessDAO($Conn, $Config, $Log, $UsersDAO, $AuthenticationDAO);

// Auth
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();
$isLi = $auth->getIsLi();
$isAdmin = $auth->isAdmin();
$isModerator = $auth->isModerator();

//Exceptions
//include $dir . 'lib/Exception/AuthException.php';
//include $dir . 'lib/Exception/IntrusionException.php';

// Access
/* @var $Access type */
$Access = $AccessDAO->getAccess(); //todo fix access
if($Access) {
    $AccessAdded = $AccessDAO->addVisit();
} else {
    $Access = $AccessDAO->addAccess();
}

//Timer
$Timer = new Timer($Log);
$Times = Array();

$this_user = $UsersDAO->getUserFromId($liId);
$all_domains = $DomainsDAO->getAllDomains();

/* @var $_SERVER callable */
$ip = $Log->getIp();

date_default_timezone_set('America/New_York');
$today = date("y-m-d");

include DIR . 'modules/disect_url.php';

if($item){
    $Log->log($liFullName, "info", "We are loading a Single post: " . $id);
    $posts[0] = $PostsDAO->getPost($id);
    $pages = 0;
    $total_count = 1;
} else {
    $Timer->start();
    $posts = $PostsDAO->getPosts($college, $search, $sort, 0, 40);
    $Timer->stop();
    $Log->log($liFullName, 'info', 'Timer: PostsDAO->getPosts() took ' . Timer::$last_time);

    $total_count = $PostsDAO->getTotalRows($PostsDAO->sql);

    $Log->log($liFullName, "info", "We are loading multiple (" . $PostsDAO->total_count . " posts total) (" . $PostsDAO->fp_count .  " posts on front-page) SQL: " . $PostsDAO->sql);

    // Choose AJAX Infinite Scrolls
    $pages = 0;
    if($posts != false){
        $pages = ceil((($total_count - 40) / 10));
    } else {
        $total_count = false;
        $pages = false;
    }
}

$subpage = FALSE; //TODO: Figure out what subpage .. looks like garbage

if($debug && $debug_location == 'head'){
    include DIR . 'modules/debug.php';
}

include(DIR . 'interface/head.php');

include(DIR . 'modules/analytics.php');

if($AuthenticationDAO->isLi()){
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
try {
    if($PostsDAO->fp_count<1  && !$PostsDAO->fp_count) {
        if ($search) {
            echo '<b>Sorry, we could not find anything in that matched your search <i>' . $search . '</i></b><';
            $Log->log($liFullName, "action", $liFullName . " searched for " . $search, "no results found");
        } else {
            echo '<b> Sorry we could not find any posts from ' . DomainsDAO::getCollegeName($college) . ', try <a href="' . $Config->get('url') . '">from the beginning</a></b>';
            $Log->log($liFullName, "action", $liFullName . " looked up " . $college, "no results found"); //todo - move these below
        }
    } else if($PostsDAO->fp_count == 1) {
            $x = 0;
            include(DIR . 'modules/post_row.php'); //We can probably remove this now because getPost returns an array anyways
    } else {
        for($x = 0; $x < $PostsDAO->fp_count; $x++){

            include(DIR . 'modules/post_row.php');

            if($count <9){
                $count++;
            } else {
                $count = 0;
            }
        }
    }
} catch(IntrusionException $e) {
    $Log->log('IP', 'action', 'Error loading post: ' . $e->getMessage());
    $Helper->print_error();
} catch(Exception $e) {
    $Log->log('IP', 'action', 'Error loading post: ' . $e->getMessage());
    $Helper->print_error();
} finally {
    
}

echo '</div><div id="loadmoreajaxloader" style="display:none;"><center><img src="' . URL . 'interface/img/ajax-loader.gif" /></center></div>';

include(DIR . 'interface/foot.php');

include(DIR . 'modules/analytics.php');
 ?>
