
<?php

include('./theme/subpage_head.php');

include('./functions.php');
include('./lib/Domains.php');
include('./lib/Users.php');
include('./lib/vers.php');
include('./lib/Posts.php');
include('./lib/Database.php');

$database = new Database();
$conn = $database->connection();

if(isset($_GET['ver'])){

	
	$ver = $_GET['ver'];
	
	$ver_object = vers::getVerification($ver, $conn);
	
	if($ver_object){		
		
		$v = $ver_object['ver']; 

		$u = $ver_object['username'];

		$d = $ver_object['domain'];
		
                echo '<div class="alert alert-info">';
                
		echo '<b>Please choose a password</b><br />';
		
		echo 'Your password must be between 4 and 20 characters long, only requirement<br />';
                
                echo '</div>';
		
		echo '<form name="passChoose" 
                        class="form-signin"
                        onsubmit="return matchPass()" 
                        action="passChoose.php" 
                        method="post">';
			echo '<input class="form-control" type="password" name="password1" value="password" /><br />';
			echo '<input class="form-control" type="password" name="password2" value="password" /><br />';
			echo '<input type="hidden" name="username" value="' . $u . '">';
			echo '<input type="hidden" name="domain" value="' . $d . '">';
			echo '<input type="hidden" name="ver" value="' . $v . '">';
			echo '<input type="hidden" name="passwordSubmitted" value="TRUE">';
			echo '<input class="btn btn-primary" type="submit" value="START MAKING $$" />';
		echo '</form>';
		
	} else {
            
		echo '<div class="alert alert-danger">We could not find your verification number</div>';
                
	}
} else if(isset($_POST['passwordSubmitted'])){
		
		$p = $_POST['password1'];
		$p2 = $_POST['password2'];
		$u = $_POST['username'];
		$d = $_POST['domain'];
		$key = $_POST['ver'];
		
		
		if($p == $p2){
			
                    $create_user_ok = users::createUser($u, $d, $p, $conn);
                    
                    $delete_ver_ok = vers::deleteVer($key, $conn);
                    
                    if($create_user_ok && $delete_ver_ok){
                        echo '<div class="alert alert-success">' . $u . '@' . $d . ' Welcome to Campus Swap</div>';
                        echo '<a href="' . Config::get('url') . 'login.php"><button class="btn btn-primary">Login</button></a>';
                    }
	
                    
		
                    
                } else {
			echo '<div class="alert alert-danger">';
                        echo 'Your passwords did not match!<br /></div>';
			echo '<form action="passChoose.php" method="GET">';
			echo '<input type="hidden" name="register" value="TRUE" />';
			echo '<input type="hidden" name="key" value="' . $key . '" />';
			echo '<input type="submit" value="Try Again" />';
			echo '</form>';
		}
		
	

			


} else {
    
    echo '<div class="alert alert-info">Please enter your Verification Code</div>';
    
    echo '<form method="GET" action="passChoose.php" class="form-signin">';
    
    echo '<input name="ver" type="text" class="form-control" placeholder="Verification Code" required="" autofocus="">';
    
    echo '<br />';
    
    echo '<button class="btn btn-lg btn-primary btn-block" type="submit">Verify</button>';
    
    
    
    echo '</form>';
    
}

return_home();

include('./theme/subpage_foot.php');

?>


