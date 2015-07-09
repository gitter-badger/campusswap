<?php
include('./lib/Config.php');

$Config = new Config('./etc/config.ini');

$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }
$version = Config::get('version');

include $dir . 'lib/DAO/PostsDAO.php';
include $dir . 'lib/DAO/DomainsDAO.php';
include $dir . 'lib/DAO/UsersDAO.php';
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/DAO/VersDAO.php');


$debug = Parser::isTrue(Config::get('debug'));

$database = new Database();
$Conn = $database->connection();
$LogUtil = new LogUtil($Conn, $Config);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil);
$DomainsDAO = new DomainsDAO($Conn, $Config, $LogUtil);
$all_domains = $DomainsDAO->getAllDomains();
$UsersDAO = new UsersDAO($Conn, $Config, $LogUtil);
$VersDAO = new VersDAO($Conn, $Config, $LogUtil);

//TODO: Cleanup the login and add failed attempt banner
//TODO: Cleanup the security system while were at it, this page is a good start


include(DIR . 'interface/subpage_head.php');


if(isset($_POST['signup'])){ //SEE IF POST signup VAR SET

    if(!empty($_POST['domain']) && !empty($_POST['username']) && ($_POST['username'] != 'College E-Mail Address')) {
        
        $username = Parser::sanitize($_POST['username']);
        $domain = $_POST['domain'];

        if(DomainsDAO::domainExists($domain, $Conn)){ //CHECK IF DOMAIN EXISTS

            if(!UsersDAO::userExists($username, $domain, $Conn)){ //CHECK IF USER BANNED

                $fullName = $username . '@' . $domain;
                $user = $UsersDAO->getUserFromName($username, $domain, $Conn);

                if($user['level'] != 'banned'){ //is banned?

                    if($VersDAO->verSent($username, $domain, 'signup')){ //Var already sent

                        $key = $VersDAO->getVerFromUser($username, $domain, 'signup');

                        echo '<div class="alert alert-warning">';
                        echo 'We have already sent you a verification email to ' . $fullName . ', we will send another one. Try checking your spam folder</div>';
//                          $key = vers::getVerFromUser($user, $domain, $Conn);

                    } else { //Create an account

                        $key = md5(uniqid(rand(), true));

                        $created_ok = $VersDAO->createVer($key, $username, $domain, 'signup');

                        if($created_ok){
                            echo '<div class="alert alert-success">We sent you an e-mail to verify your status at ' . $domain . '</div>';

                        } else {
                            echo '<div class="alert alert-danger>There was a problem creating your account</div>';
                        }
                    }

                    Helper::return_home_button();
                    echo '<a class="center" href="' . Config::get("url") . 'passChoose.php"><button class="btn btn-primary">Enter Key</button></a>';

                    $theURL = Config::get('url');

                    $email = $fullName;

                    $subject = 'Campus Swap - Verification Code!';

                    $content = '<h1>Welcome to Campus Swap!</h1>';
                    $content .= 'Click this link to verify your email adress and start selling!</p>';
                    $content .= ' ' . $theURL . 'passChoose.php?key=' . $key . ' ';

                    $headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers .= 'From: user_contact@campusswap.net' . "\r\n";

                    echo '<div class="alert alert-warning>' . $content . ' </div>';

                    mail($email, $subject, $content, $headers);

                    echo '<div class="alert alert-warning>' . $theURL . 'passChoose.php?key=' . $key . ' </div>';

                } else {
                    echo '<div class="alert alert-danger">Your email address ' . $fullName . ' has been banned from Campus Swap, your IP has been logged</div>';
                    $log->log("IP", "action", "banned user - attempted login - " . $fullName);
                }

            } else {
                echo '<div class="alert alert-warning">You already have an account at Campus Swap, If you forgot your password you can recover it.</div>';
                Helper::return_home_button();
                echo '<a href="' . Config::get("url") . 'recoverPassword.php"><button type="button" class="btn btn-primary">Recover Password</button></a>';
            }
        } else {
            echo '<div class="alert alert-warning">Your domain is not a domain we allow on Campus Swap, you can contact us and we may add it</div>';
            Helper::return_home_button('Try Again');
        }
        
    } else {
        echo '<div class="alert alert-warning">You did not enter an email address. Try again!</div>';
        Helper::return_home_button('Try Again');

    }
        
}




include(DIR . 'interface/subpage_foot.php');


?>

			


