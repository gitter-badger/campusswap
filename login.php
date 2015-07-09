<?php
//session_start();

include('./lib/Config.php');

$Config = new Config('./etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }
$version = Config::get('version');

include($dir . 'lib/DAO/DomainsDAO.php');
include($dir . 'lib/DAO/AccessDAO.php');
include($dir . 'lib/DAO/UsersDAO.php');


include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Util/LogUtil.php');

include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');

$debug = Parser::isTrue(Config::get('debug'));

$database = new Database();
$Conn = $database->connection();

$LogUtil = new LogUtil($Conn, $Config);
$DomainsDAO = new DomainsDAO($Conn, $Config, $LogUtil);
$AccessDAO = new AccessDAO($Conn, $Config, $LogUtil);
$UsersDAO = new UsersDAO($Conn, $Config, $LogUtil);

$all_domains = $DomainsDAO->getAllDomains();

//TODO: Cleanup the login and add failed attempt banner
//TODO: Cleanup the security system while were at it, this page is a good start


include(DIR . 'interface/subpage_head.php');


if(isset($_POST['loginSubmitted'])){
	
	$errors = array();
	
	if(!isset($_POST['username'])){
		$errors[] = 'You did not enter a username!';
	} else {
		$username = $_POST['username'];
	}

    $domain = $_POST['domain'];
	
	if(!isset($_POST['password'])){
		$errors[] = 'You did not enter a password';
	} else {
		$password = $_POST['password'];
	}
	
	$fullName = $username . '$' . $domain;
	
	if(empty($errors)){
		
            date_default_timezone_set('America/New_York');
            $today = date("y-m-d");

            $check = false;
		
            $user_authenticated = $UsersDAO->authenticateUser($username, $domain, $password);
            $access = $AccessDAO->getAccessToday(LogUtil::getIp(), $fullName);

            $failed_logins = $access->getFailedLogins();

		if($user_authenticated){ //If user + password good
			
			if($failed_logins > 5 || $user_authenticated == 'banned'){ //If Banned or Too many Login attempts

				echo 'You have been banned or you have more than 5 failed login attempts in the past 24 hours, your IP has been logged.';
				if($failed_logins > 5){
                                    $alert = 'Your IP has been temporarily banned becase your IP has failed more than 5 logon attempts today. If you beleive this is incorrect - please contact support.';
                                    Helper::print_alert("danger", $alert);
                                    $LogUtil->log("IP", "ACTION", $username . "@" . $domain . " failed to enter correctly password more than 5 times. Failed login attempt #" . $failed_logins);
                                }

                        if($user_authenticated == 'banned') {
                            $LogUtil->log("IP", "ACTION", $username . "@" . $domain . "Banned User attempted login", "banned user login attempt");
                            Helper::print_alert("warning", "Your username has been banned, you cannot log in. If you think this has been done in error, contact support");
                        }

			} else { //Login successful
				$_SESSION['user'] = $user_authenticated->getUsername();
				$_SESSION['domain'] = $user_authenticated->getDomain();
				$_SESSION['userId'] = $user_authenticated->getId();
				$_SESSION['level'] = $user_authenticated->getLevel();
				
                            $access_usernames = explode("/", $access->getUsernames());
                            if(!in_array($fullName, $access_usernames)){
                                $access->addUsertoAccess($Conn, $fullName); //Might to include the file at the top and redlecare
                            }

                            $LogUtil->log($fullName, 'action', 'login - ' . $fullName - ' . IP: ' . LogUtil::getIp());
                            echo '<div class="alert alert-success">You have successfully logged in</div>';
			}
				
		} else {
                    echo '<div class="alert alert-warning">Could not find the username or password. If you repeatedly login incorrectly your IP address will be banned.</div><br />';
                    echo '<a href="' . $url . 'login.php"><button class="btn btn-primary">Try Login Again</button></a>';
                    $LogUtil->log('IP', 'action' , ' failed login - ' . $username . '@' . $domain);
		}
	} else {
		
		foreach($errors as $msg){
            $LogUtil->log('IP', 'ACTION' , ' failed login error: ' . $msg, 'failed login');
            echo '<div class="alert alert-danger"><a href="login.php">' . $msg . '</a></div>';
		}
        Helper::return_home_button();
	}
} else {

//TODO: Finish Login Page UI

?>

<h1>Login</h1>
<form name="input"
          class="form-signin"
          action="login.php"
          method="post">
        <h4><b>Username: </b></h4>
    <input type="text" class="tall_text_box input-group input-group-lg" size="10" value="username" name="username" /><br />
    <select class="form-control input-lg" name="domain">
        <?php
        foreach($all_domains as $row){
            echo '<option value = "' . $row->getDomain() . '">@' . $row->getDomain() . '</option>';
        }
        ?>
    </select><br />
            <h4><b>Password: </b></h4>
    <input class="tall_text_box form-control input-lg" type="password" size="30" name="password" value="password" /><br />
    <input type="hidden" name="loginSubmitted" value="TRUE">
    <input type="submit" class="btn btn-primary btn-large btn-block" value="Login" />
    <a href="<?= Config::get('url'); ?>recoverPassword.php">Recover Password</a>
</form>

<h2>Register<small>Available Upon Release</small></h2>
<form name="input" action="signup.php" method="post">
        <h4><b>University E-Mail: </b></h4>
            <input class="tall_text_box input-group input-grou-lg" type="text" size="10" value="username" name="username" /><br />
    <select class="form-control input-lg" name="domain">
        <?php
        foreach($all_domains as $row){
            echo '<option value = "' . $row->getDomain() . '">@' . $row->getDomain() . '</option>';
        }
        ?>
    </select><br />
    <input type="hidden" name="signup" value="TRUE">
    <input type="submit" class="btn btn-primary btn-large btn-block" value="Signup!" />

</form>

<?php } 

 Helper::return_home_button();

include(DIR . 'interface/subpage_foot.php');


?>