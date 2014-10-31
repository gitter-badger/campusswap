<?php

include('./lib/Config.php');
$config = new Config('./etc/config.ini');
$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');

include($dir . 'functions.php');
include($dir . 'lib/Domains.php');
include($dir . 'lib/Users.php');
include($dir . 'lib/vers.php');
include($dir . 'lib/Posts.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/Authentication.php');
include($dir . 'lib/Log.php');

include('./theme/subpage_head.php');

$database = new Database();
$conn = $database->connection();


if(isset($_POST['loginSubmitted'])){
	
	$errors = array();
	
	if(empty($_POST['username'])){
		$errors[] = 'You did not enter a username!';
	} else {
		$u = $_POST['username'];
	}
	
	$u = $_POST['username'];
	
	if(empty($_POST['password'])){
		$errors[] = 'You did not enter a password';
	} else {
		$p = $_POST['password'];
	}
	
	$d = $_POST['domain'];
	
	$fullName = $u . '$' . $d;
	
	if(empty($errors)){
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		date_default_timezone_set('America/Denver');
		$today = date("y-m-d");
		
		$ipQuery = mysqli_query($conn, "SELECT failed_logons FROM ipLog WHERE (ip = '$ip' AND date = '$today')");
		
		$ipArray = mysqli_fetch_array($ipQuery, MYSQL_NUM);
		
		$failed_logons = $ipArray[0];
		
		$check = false;
		
		$userSQL = mysqli_query($conn, "SELECT username, domain, level, id, status FROM users WHERE (username='$u' AND domain='$d' AND password=SHA('$p'))");
		
		$userSQLCount = mysqli_num_rows($userSQL);
		
		$row = mysqli_fetch_array($userSQL, MYSQL_NUM);

		if($userSQLCount != 0){ //If found user
			
			if($row[4]=='banned' || $failed_logons > 5){ //If Banned or Too many Login attempts
				echo 'You have been banned, your IP has been logged.';
				if($ipArray[0] < 5){
					echo '<div class="alert alert-danger">Your ip has failed logon attempts today greater than 5 times, try again tomorrow</div>';
					Log::logAction($_SERVER['REMOTE_ADDR'], $u . '@' . $d . ' failed to enter correct password more than 5 times');
					
				}
				Log::logAction($_SERVER['REMOTE_ADDR'], 'Banned User attempted login, user ' . $u . '@' . $d);
				
			} else { //Login successfully
				$_SESSION['user'] = $row[0];
				$_SESSION['domain'] = $row[1];
				$_SESSION['userId'] = $row[3];
				$_SESSION['level'] = $row[2];

				$fullName = $_SESSION['user'] . '@' . $_SESSION['domain'];
				
				$ipUsernameSQL = mysqli_query($conn, "SELECT usernames FROM ipLog WHERE (ip = '$ip' AND date = '$today')");
				$ipUsername = mysqli_fetch_array($ipUsernameSQL, MYSQL_NUM);
				
				$ipUsernames = explode("/", $ipUsername[0]);
				
				
				$foundUserIp = FALSE;
				foreach($ipUsernames as $i){
					if($i == $fullName){
						$foundUserIp = TRUE;
					} else {
						//Do Nothing
					}
				}
				
				$updateUsername = $ipUsername[0] . '/' . $fullName;
				
				if(!$foundUserIp){
					//mysql_query("UPDATE ipLog SET usernames = CONCAT(usernames, '$updateUsername') WHERE (ip = '$ip' AND date = '$today')");
				}

				Log::logAction($fullName, 'logged in successfully');

				echo '<div class="alert alert-success">you have successfully logged in</div>';
			}
				
		} else {
			echo '<div class="alert alert-warning">Could not find the username or password and your ip address has been logged. If you repeatedly login incorrectly your IP address will be banned.</div><br />';
			
                        echo '<a href="login.php"><button class="btn btn-primary">Try Login Again</button></a>';
                        
                        mysqli_query($conn, "UPDATE ipLog SET failed_logons = failed_logons +1 WHERE (ip = '$ip' AND date = '$today')");
			Log::logAction($_SERVER['REMOTE_ADDR'], 'Incorrect Password attempt, user ' . $u . '@' . $d);
		}
	} else {
		
		foreach($errors as $msg){
			echo $msg ;
		}
		echo '<div class="alert alert-danger"><a href="login.php">There was an error, Try Again</a></div>';
                return_home();
	}
} else { ?>



    <h2>Login</h2>
	<form name="input" 
              class="form-signin"
              action="login.php" 
              method="post">
            <h4><b>Username: </b></h4>
		<input type="text" class="tall_text_box input-group input-group-lg" size="10" value="username" name="username" /><br />
		<select class="form-control input-lg" name="domain">
			<?php
			$domain_result = mysqli_query($conn, "SELECT domain, name FROM domains");

			while($row = mysqli_fetch_assoc($domain_result)){
				echo '<option value = "' . $row['domain'] . '">@' . $row['domain'] . '</option>';
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
			$domain_result = mysqli_query($conn, "SELECT domain, name FROM domains");

			while($row = mysqli_fetch_assoc($domain_result)){
				echo '<option value = "' . $row['domain'] . '">@' . $row['domain'] . '</option>';
			}

			?>
		</select><br />
		<input type="hidden" name="signup" value="TRUE">
		<input type="submit" class="btn btn-primary btn-large btn-block" value="Signup!" />
		
	</form>
        

</form>

<?php } 

 return_home();

include('./theme/subpage_foot.php'); 


?>