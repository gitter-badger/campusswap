<?php

include('./lib/Config.php');

$Config = new Config('./etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/UsersDAO.php');
include($dir . 'lib/DAO/VersDAO.php');
include($dir . 'lib/DAO/DomainsDAO.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');
include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Util/LogUtil.php');

include($dir . 'lib/Database.php');


$debug = Parser::isTrue(Config::get('debug'));

$AuthenticationDAO = new AuthenticationDAO($Conn, $Config, $Log, $UsersDAO);
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();
$isLi = $auth->getIsLi();

$database = new Database();
$Conn = $database->connection();

$LogUtil = new LogUtil($Conn, $Config);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil);
$UserDAO = new UsersDAO($Conn, $Config, $LogUtil);
$DomainsDAO = new DomainsDAO($Conn, $Config, $LogUtil);
$all_domains = $DomainsDAO->getAllDomains();
$VersDAO = new VersDAO($Conn, $Config, $LogUtil);


include(DIR . 'interface/subpage_head.php');

//TODO: Finish Password Recovery

if(isset($_POST['recover'])) {
	
	$username = $_POST['username'];
	$domain = $_POST['domain'];
	$fullName = $username . '@' . $domain;

	$key = md5(uniqid(rand(), true));

    if($UserDAO->userExists($username, $domain, $Conn)) {

        if ($VersDAO->verSent($username, $domain, 'recover')) { //Var already sent

            $key = $VersDAO->getVerFromUser($username, $domain, 'recover');

            echo '<div class="alert alert-warning">';
            echo 'We have already sent you a verification email to ' . $fullName . ', we will send another one. Try checking your spam folder</div>';

        } else { //Create an account

            $key = md5(uniqid(rand(), true));

            $created_ok = $VersDAO->createVer($key, $username, $domain, 'recover');

            if ($created_ok) {
                echo '<div class="alert alert-success">We have sent an email to ' . $fullName . ' containing a verification key</div>';

            } else {
                echo '<div class="alert alert-danger>There was a problem creating your account</div>';
            }
        }

        Helper::return_home_button();
        echo '<a class="center" href="' . Config::get("url") . 'passChoose.php"><button class="btn btn-primary">Enter Key</button></a>';

        $theURL = Config::get('url');
        $recoverURL = $theURL . 'passChoose.php?key=' . $key . ' ';

        $email = $fullName;
        $subject = 'CampusSwap.net - Password Recovery!';

        $content = '<h1>Welcome to Campus Swap!</h1>';
        $content .= 'CampusSwap.net - to recover your password, click <a href="' . $recoverURL . '"> Here</a><br />';
        $content .= 'Or go to: ' . $recoverURL . '<br><br>Thanks,<br>Campus Swap';


        $headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: user_contact@campusswap.net' . "\r\n";

        echo '<div class="alert alert-warning>' . $content . ' </div>';

        mail($email, $subject, $content, $headers);

        echo '<div class="alert alert-warning>' . $theURL . 'passChoose.php?key=' . $key . ' </div>';

    } else {
        echo '<div class="alert alert-warning">You do not have an account at Campus Swap, please <a href="' . Config::get("url") . 'login.php">Register</a> for an account before proceeding</div>';
    }

} else {

    echo '<form action="' . Config::get("url") . 'recoverPassword.php" method="post">';

    echo '<div class="alert alert-warning">';
	echo 'If you have lost your password, you can recover it here. Check your email for a password reset link.</div>';

    echo '<input type="text" class="tall_text_box input-group input-group-lg" size="10" value="username" name="username" /><br />';
    echo '<select class="form-control input-lg" name="domain">';

    foreach($all_domains as $row){
        echo '<option value = "' . $row->getDomain() . '">@' . $row->getDomain() . '</option>';
    }

    echo '</select><br />';

	echo '<input type="hidden" name="recover" value="true">';
    Helper::return_home_button();
	echo '<input type="submit" class="btn btn-primary" value="Recover Password"/>';
	echo '</form>';
}

?>
</div>


</html>