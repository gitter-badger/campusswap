<?php
use Campusswap\Util\Config,
        Campusswap\Util\LogUtil,
        Campusswap\Util\Parser,
        Campusswap\Util\Database,
        Campusswap\DAO\PostsDAO,
        Campusswap\DAO\UsersDAO,
        Campusswap\DAO\AuthenticationDAO;

$Parser = new Parser();
$Config = new Config('../etc/config.ini');

$dir = $Config->get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = $Config->get('url'); if(!defined('url')) { define ('URL', $url); }


$debug = $Parser->isTrue($Config->get('debug'));
//Also add timer

$database = new Database($Config);
$Conn = $database->connection();

$LogUtil = new LogUtil($Conn, $Config, $Parser);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil);
$UsersDAO = new UsersDAO($Conn, $Config, $LogUtil);

$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $Log, $UsersDAO, $Parser);
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$isLi = $auth->getIsLi();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();

$simple = true;
include $dir . 'interface/subpage_head.php';

if(isset($_GET['key'])){

    $key = $_GET['key'];

    $verification = $VersDAO->getVerification($key);

    if($verification){

            $ver = $verification->getVer();

            $username = $verification->getUsername();

            $domain = $verification->getDomain();

    $type = $verification->getType();

    echo '<div class="alert alert-warning">';

            echo '<b>Please choose a password</b><br />';

            echo 'Your password must be between 4 and 20 characters long<br />';

    echo '</div>';

            echo '<form name="passChoose"
                    class="form-signin"
                    onsubmit="return matchPass()"
                    action="passChoose.php"
                    method="post">';
                    echo '<input class="form-control" type="password" name="password1" value="password" /><br />';
                    echo '<input class="form-control" type="password" name="password2" value="password" /><br />';
                    echo '<input type="hidden" name="username" value="' . $username . '">';
                    echo '<input type="hidden" name="domain" value="' . $domain . '">';
                    echo '<input type="hidden" name="ver" value="' . $key . '">';
        echo '<input type="hidden" name="type" value="' . $type . '">';
                    echo '<input type="hidden" name="passwordSubmitted" value="TRUE">';
                    echo '<input class="btn btn-primary" type="submit" value="START MAKING $$" />';
            echo '</form>';

    } else {

            echo '<div class="alert alert-danger">We could not find your verification number</div>';

    }
} else if(isset($_POST['passwordSubmitted'])){

            $password = $_POST['password1'];
            $password2 = $_POST['password2'];
            $username = $_POST['username'];
            $domain = $_POST['domain'];
            $key = $_POST['ver'];
    $type = $_POST['type'];

            if($password == $password2){

        if($type == 'signup') {
            $update = $UsersDAO->createUser($username, $domain, $password);
        } elseif($type == 'recover') {
            $update = $UsersDAO->updatePassword($username, $domain, $password);
        }

        $delete_ver_ok = $VersDAO->deleteVer($key);

        if($update && $delete_ver_ok){
            if($type=='signup') {
                echo '<div class="alert alert-success">' . $username . '@' . $domain . ' Welcome to Campus Swap</div>';
            } elseif($type == 'recover') {
                echo '<div class="alert alert-success">' . $username . '@' . $domain . ' Your password has succesfully been reset! Welcome Back!</div>';
            }

            echo '<a href="' . $Config->get('url') . 'login.php"><button class="btn btn-primary">Login</button></a>';
        } else {
            echo '<div class="alert alert-error">There was a problem creating your account, updating your password or deleting the verification key</div></div>';
        }
    } else {
                    echo '<div class="alert alert-danger">';
        echo 'Your passwords did not match!<br /></div>';
                    echo '<form action="passChoose.php" method="GET">';
                    echo '<input type="hidden" name="register" value="TRUE" />';
                    echo '<input type="hidden" name="key" value="' . $key . '" />';
                    echo '<input type="submit" value="Try Again" />';
                    echo '</form>';
        $Helper->return_home_button(null, 'long');
		}
} else {
    
    echo '<div class="alert alert-info">Please enter your Verification Code</div>';
    
    echo '<form method="GET" action="passChoose.php" class="form-signin">';
    
    echo '<input name="key" type="text" class="form-control" placeholder="Verification Code" required="" autofocus="">';
    
    echo '<br />';
    
    echo '<button class="btn btn-lg btn-primary btn-block" type="submit">Verify</button>';

    $Helper->return_home_button(null, 'long');
    
    echo '</form>';
    
}

include('./interface/subpage_foot.php');

?>


