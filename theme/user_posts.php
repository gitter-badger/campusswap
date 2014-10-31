<html>
<head>
	<link href="./css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="./css/bootstrap3_forms_glyph.css" rel="stylesheet" media="screen">
	
	<style type="text/css">

	a {
		text-decoration:none;
	}
	
	body {
		margin:15px;
	}
	</style>
	</head>
	<body>

<?php
include('../lib/Config.php');

$system = 'CONFIG-DEV';
$config = new Config('../etc/config.ini', $system);


$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

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

Logger::configure($dir . 'etc/log4php.xml');
$log = Logger::getLogger("main");

$liUser = Authentication::liUser();
$liDomain = Authentication::liDomain();
$liId = Authentication::liId();
$liLevel = Authentication::liLevel();

$database = new Database();
$conn = $database->connection();

$PostsDAO = new PostsDAO($conn);

if(Authentication::isLi()){

$posts = $PostsDAO->getPostsUser($conn, $liUser, $liDomain);

echo '<table border="0" cellspacing="0" cellpadding="0" style=";font-size:1em;">';

$rowDescription = null;

echo '<div style="padding-left:2px;border-bottom:1px white solid"><h1>' . liUser() . '@' . liDomain() . ' posts</h1>';

echo '&nbsp;<a class="btn btn-default btn-sm" href="' . Config::get('url') . 'contact.php?contact=bug" target="_top">Report a bug</a>';

echo '&nbsp;<a class="btn btn-default btn-sm" href="' . Config::get('url') . 'disclaimer.php" target="_top">Disclaimer</a>';

echo '&nbsp;<a class="btn btn-default btn-sm" href="' . Config::get('url') . 'contact.php" target="_top">Contact</a>';

echo '&nbsp;<a class="btn btn-default btn-sm" href="' . Config::get('url') . 'deleteAccount.php" target="_top">Delete Account</a>';

echo '</div>';

for($x = 0; x < count($posts); $x++){

    $rowDescription = substr($posts[$x]->getDescription(), 0, 180) . "...";

    $rowItem = substr($posts[$x]->getItem(), 0, 20);

    date_default_timezone_set('America/Denver');
    $deleted = new DateTime($posts[$x]->getCreated());
    $todaysDate = date('d.m.y');
    $today = new dateTime($todaysDate);
    $deleted->modify("+60 day");

    $interval = $today->diff($deleted);


    echo '<tr>';
        echo '<td><h4>' . $rowItem . '</td><td><small>&nbsp;&nbsp;(' . $interval->format('%R%a') . ' days left)</small></h4>&nbsp;</td><td>&nbsp;' . $rowDescription . '</td>';

        echo '<td style="padding-top:5px"> <form target="_top" name="editItem" action="modifyItem.php" method="post">';

        echo '<input type="hidden" value="' . $posts[$x]->getId() . '" name="id">';
        echo '<input type="hidden" name="edit" value="true">';

        echo '&nbsp;&nbsp;<input  class="btn btn-warning" type="submit" value="Edit" />&nbsp;&nbsp;';

        echo '</form></td>';

        echo '<td style="padding-top:5px"> <form target="_top" name="deleteItem" action="modifyItem.php" method="post">';

        echo '<input type="hidden" value="' . $posts[$x]->getId() . '" name="id">';

        echo '<input type="hidden" name="delete" value="true">';
        echo '<input class="btn btn-danger" target="_top" type="submit" value="Delete" />';
        echo '</form></td>';
    echo '</tr>';
}

echo '</table>';
} else { ?>


	<?php /*<div id="login_link" style="background:#6d8ca0;<?= $display ?>height:100px;clear:both;padding-right:10px;padding-bottom:10px; margin-bottom:20px;border-bottom:1px solid white;">*/ ?>

	<table>
	<tr>
		<td>			
			<div style="padding-right:15px; padding-bottom:25px; padding-top:25px;border-right:1px solid white;margin-left:10px">
				<h1>Welcome!</h1>
			</div>
		</td>

		<td>
	<div style="margin-top:0px;padding-left:0px;margin-left:0px">
	<ul>
		<li style="padding-bottom:4px;">To sign up for Campus Swap you must have a valid university email address</li>
		<li style="padding-bottom:4px;">To contact sellers you must have a valid CollegeHuster account</li>
		<li style="padding-bottom:4px;">Your email address will never be revealed to anyone</li>
		<li style="padding-bottom:4px;">Read the rules for CampusSwap, <a href="<?= Config::get('url') ?>disclaimer.php"><b>disclaimer!</b></a></li>
	</ul>
	</div>
	</td>
	<td>,
	<div style="padding-left:15px; border-left:1px solid white;padding-right:50px;margin-left:15px; border-right:1px solid white;">
		<b>One Click Sign Up!!</b>
		<form action="signup.php?signup" method="post">
		<input type="text" size="11" name="username" "/>
		<select name="domain">
			<?php
			$domain_result = mysqli_query("SELECT domain, name FROM domains");

			while($row = mysqli_fetch_assoc($domain_result)){
				echo '<option value = "' . $posts[$x]->getDomain() . '">@' . $posts[$x]->getDomain() . '</option>';
			}

			?>
		</select>
		<input type="submit" value="Start hustling" />
		</form><br />
		<i>Is your university not listed? <a href="contact.php">Contact Us<a/></i><br />
	</div>
	</td>
	<td>
	<div style="padding-left:10px; padding-top:10px">

		<form name="input" action="login.php" method="post">
			<input type="text" size="10" value="username" name="username" />
			<select name="domain">
				<?php
				$domain_result = mysqli_query("SELECT domain, name FROM domains");

				while($row = mysql_fetch_assoc($domain_result)){
					echo '<option value = "' . $posts[$x]->getDomain() . '">@' . $posts[$x]->getDomain(). '</option>';
				}

				?>
			</select><br />
			<input type="password" size="25" name="password" value="password" /><br />
			<input type="hidden" name="loginSubmitted" value="TRUE">
			<input type="submit" value="Login" />
			<a href="<?= URL ?>recoverPassword.php">Recover Password</a>
		</form>
	</div>
	</td>
	</tr>
	</table>
	
</body>
</html>

<?php	
} 

?>