<?php 

//TODO: Install PhpING
//TODO: Create OCEF - Open College Ecommerce Framework with PhpIng build tool + admin section

session_start();

use Campusswap\Util\Config,
        Campusswap\Util\Parser,
        Campusswap\Util\LogUtil,
        Campusswap\Util\Database,
        Campusswap\DAO\PostsDAO,
        Campusswap\DAO\DomainsDAO,
        Campusswap\DAO\UsersDAO,
        Campusswap\DAO\AccessDAO,
        Campusswap\DAO\AuthenticationDAO;

$Parser = new Parser();
$Config = new Config('../etc/config.ini');

// Variables
$dir = $Config->get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = $Config->get('url'); if(!defined('url')) { define ('URL', $url); }
$version = $Config->get('version');
$enviorment = $Config->get('enviorment');

// DB
try {
    $database = new Database($Config);
    $Conn = $database->connection();
} catch (Exception $ex) {
    $Helper->print_error("Could not establish a database connection");
    //todo: make a simple logger by overloading the LogUtil constructor to run w/out a db connection to log this error
    die('Could not establish a DB Connection');
}
//Debug
$debug = $Parser->isTrue($Config->get('debug'));
$debug_location = $Parser->isTrue($Config->get('debug_location'));

// DAO's and Log util

$Log = new LogUtil($Conn, $Config, $Parser);
$PostsDAO = new PostsDAO($Conn, $Config, $Log);
$DomainsDAO = new DomainsDAO($Conn, $Config, $Log);
$UsersDAO = new UsersDAO($Conn, $Config, $Log);
$AccessDAO = new AccessDAO($Conn, $Config, $Log);
$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $Log, $UsersDAO, $Parser);

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


try {

?>

<?php if($debug){ ?>
	<div style="background-color:#d0ddcf;border: 1px solid #9CAA9C; margin-top:0px;">
		<b>DEBUGGING PANEL:</b> - 
		<?php echo liUser() . '@' . liDomain() . ' - ID(' . $AuthenticationDAO->getLiId() . ')'; ?>
	</div>
<?php } ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Campusswap Admin Panel</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="<?= URL ?>interface/img/favicon.ico">
    <link rel="apple-touch-icon" href="<?= URL ?>interface/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= URL ?>interface/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= URL ?>interface/img/apple-touch-icon-114x114.png">
    
    
    <!-- Scripts: inc. TableSorter -->
        <script src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
    <script src="http://autobahn.tablesorter.com/jquery.tablesorter.min.js"></script>
    <script src="assets/js/google-code-prettify/prettify.js"></script>
    <script src="assets/js/application.js"></script>
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Campus Swap</a>
          <div class="nav-collapse">
            <ul class="nav">
                <li class="active"><a href="<?= $url ?>admin/"><b>Home</b></a></li>
                    <li><a href="<?= $url ?>"><b>MAIN SITE</b></a></li>
                    <li><a href="<?= $url ?>admin/access.php">Access</a></li>
                    <li><a href="<?= $url ?>admin/users.php">Users</a></li> 
                    <li><a href="<?= $url ?>admin/posts.php">Posts</a></li>
                    <li><a href="<?= $url ?>admin/log.php">Log</a></li>
                    <li><a href="<?= $url ?>admin/vers.php">Vers</a></li>
                    <li><a href="<?= $url ?>admin/domains.php">Domains</a></li>
                    <li><a href="<?= $url ?>admin/ip.php">IP</a></li>
                    <li><a href="<?= $url ?>admin/addUsers.php">Add Users</a></li>
                    <li><a href="<?= $url ?>admin/addPosts.php">Add Posts</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	<div style="margin-top:40px;margin-bottom:40px;margin-right:50px;margin-left:50px;">
    


<?php 
} catch(AuthException $ae) {
    
    $Helper->print_error($ae ->getInstruction());
    die("Logic stopped because of an Authentication Error");
    
} catch(IntrusionException $ie) {
    
    $AccessDAO->addIntrusion();
    if($Parser->isEqual($ie->getIntrusion(), 'failed-login')) {
        $AccessDAO->addFailedLogin();
    }
    $LogUtil->log('IP', 'action', 'Admin Section Intrusion', $ie);
    $Helper->print_error();
    die("Logic stopped because of an Intrusion Detected");
    
} catch(Exception $e) {
    
    //todo - possibly log intrusion or failed login
    $LogUtil->log('IP', 'error', 'login error', $e);
    $Helper->print_error();
    die("Logic stopped because of an error");
} 
?>