<?php

use Campusswap\Util\Config,
        Campusswap\Util\Parser,
        Campusswap\Util\LogUtil,
        Campusswap\Util\Database,
        Campusswap\DAO\DomainsDAO,
        Campusswap\DAO\AccessDAO;

session_start();

$Parser = new Parser();
$Config = new Config('./etc/config.ini');

$dir = $Config->get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = $Config->get('url'); if(!defined('url')) { define ('URL', $url); }
$version = $Config->get('version');

$debug = $Parser->isTrue($Config->get('debug'));

$database = new Database($Config);
$Conn = $database->connection();

$LogUtil = new LogUtil($Conn, $Config, $Parser);
$DomainsDAO = new DomainsDAO($Conn, $Config, $LogUtil);
$AccessDAO = new AccessDAO($Conn, $Config, $LogUtil);
$UsersDAO = new sersDAO($Conn, $Config, $LogUtil);

$all_domains = $DomainsDAO->getAllDomains();

//TODO: Cleanup the login and add failed attempt banner
//TODO: Cleanup the security system while were at it, this page is a good start


include(DIR . 'interface/subpage_head.php');


if(isset($_POST['loginSubmitted'])){
    try {
        $errors = array();

        if(empty($_POST['username'])){
            throw new AuthException("Username field is empty", "Please enter a username");
        } else {
            $username = $_POST['username'];
        }
        
        if(empty($_POST['domain'])){
            throw new AuthException("Domain field is empty", "Please Select a Domain");
        } else {
            $domain = $_POST['domain'];
        }

        if(empty($_POST['password'])){
            throw new AuthException("Password field is empty", "Please enter a password");
        } else {
            $password = $_POST['password'];
        }

        $fullName = $username . '$' . $domain;

        date_default_timezone_set('America/New_York');
        $today = date("y-m-d");
        $check = false;
        
        $user_authenticated = $UsersDAO->authenticateUser($username, $domain, $password);
        $access = $AccessDAO->getAccess(); 
        if($access) {
            $intrusions = $access->getIntrusions();
            $failed_logins = $access->getFailedLogins();
        } else {
            $access = $AccessDAO->addAccess();
        }
        //TODO: Add an Auth check, if the user is banned, or too many intrusions then permenantly ban username
        
        if($user_authenticated){ //If user + password good
            if($failed_logins > 5 || $user_authenticated == 'banned' || $intrusions > 5){ //If Banned or Too many Login attempts
                if($failed_logins > 5) {
                    throw new IntrusionException($full_name, 'Login Attempt blocked - too many failed logins');
                }
                if(strcasecmp($user_authenticated, 'banned') == 0) {
                    throw new IntrusionException($full_name, 'Login Attempt blocked - username is banned');
                }
                if($intrusions > 5) {
                    throw new IntrusionException($full_name, 'Login Attempt blocked - too many intrusions');
                }
            } else { //Login successful
                //todo - add a Hash Key to DB and delete after they logout + R&D
                $_SESSION['user'] = $user_authenticated->getUsername();
                $_SESSION['domain'] = $user_authenticated->getDomain();
                $_SESSION['userId'] = $user_authenticated->getId();
                $_SESSION['level'] = $user_authenticated->getLevel();
                $fullName = $user_authenticated->getFullName();
                $_SESSOPM['fullName'] = $fullName;

                if($access) {
                    if(!$access->hasUser($Conn, $fullName)) {
                        $access->addUser($Conn, $fullName);
                    }
                } else {
                    throw new Exception("impossible result - access object is not created on login.php, when it should have been");
                }
                
                $LogUtil->log($fullName, 'action', 'login - ' . $fullName - ' . IP: ' . $LogUtil->getIp());
                echo '<div class="alert alert-success">You have successfully logged in</div>';
            }
        } else {
            echo '<div class="alert alert-warning">Could not find the username or password. If you repeatedly login incorrectly your IP address will be banned.</div><br />';
            echo '<a href="' . $url . 'login.php"><button class="btn btn-primary">Try Login Again</button></a>';
            
            throw new IntrusionException("failed-login");
        }
    } catch(AuthException $ae) { //INCORRECT CREDENTIALS AuthException
        $Helper->print_error($ae->getMessage() . " - " . $ae ->getInstruction());
        $LogUtil->log('IP', 'action' , ' failed login - ' . $username . '@' . $domain, $ae);
        $AccessDAO->addFailedLogin();
    } catch(IntrusionException $ie) {
        $AccessDAO->addIntrusion(); //INTRUSION EXCEPTION
        if($Parser->isEqual($ie->getIntrusion(), 'failed-login')) {
            $AccessDAO->addFailedLogin();
        }
        $LogUtil->log('IP', 'action', 'intrusion - ' . $ie->getMessage(), $ie);
    } catch(Exception $e) { //ERROR EXCEPTION
        //todo - possibly log intrusion or failed login
        $LogUtil->log('IP', 'error', 'login error', $e);
    } finally {
        $Helper->return_home_button();
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
    <a href="<?= $Config->get('url'); ?>recoverPassword.php">Recover Password</a>
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
     $Helper->return_home_button();
</form>

<?php } 



include(DIR . 'interface/subpage_foot.php');


?>