<?php 

include('../lib/Config.php');

$config = new Config('../etc/config.ini');

$dir = Config::get('dir');

include($dir . 'functions.php');
include($dir . 'lib/Domains.php');
include($dir . 'lib/Users.php');
include($dir . 'lib/vers.php');
include($dir . 'lib/Posts.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/Authentication.php');


?>

<?php if($debug){ ?>
	<div style="background-color:#d0ddcf;border: 1px solid #9CAA9C; margin-top:0px;">
		<b>DEBUGGING PANEL:</b> - 
		<?php echo liUser() . '@' . liDomain() . ' - ID(' . Authentication::liId() . ')'; ?>
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
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
    
    
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
          <a class="brand" href="#">College Hustler.com</a>
          <div class="nav-collapse">
            <ul class="nav">
                <li class="active"><a href="<?= Config::get('url') ?>admin/"><b>Home</b></a></li>
				<li><a href="<?= Config::get('url') ?>"><b>MAIN SITE</b></a></li>
				<li><a href="<?= Config::get('url') ?>admin/Users.php">Users</a></li> 
				<li><a href="<?= Config::get('url') ?>admin/Posts.php">Posts</a></li>
				<li><a href="<?= Config::get('url') ?>admin/log.php">Log</a></li>
				<li><a href="<?= Config::get('url') ?>admin/vers.php">Vers</a></li>
				<li><a href="<?= Config::get('url') ?>admin/domains.php">Domains</a></li>
				<li><a href="<?= Config::get('url') ?>admin/ip.php">IP</a></li>
				<li><a href="<?= Config::get('url') ?>admin/addUsers.php">Add Users</a></li>
				<li><a href="<?= Config::get('url') ?>admin/addPosts.php">Add Posts</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	<div style="margin-top:40px;margin-bottom:40px;margin-right:50px;margin-left:50px;">
    

