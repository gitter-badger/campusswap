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




if(isset($_POST['sellerEmail']) && isLi()){
	$email = $_POST['sellerEmail'];
	$item = $_POST['item'];
	
	
	echo '<div class="alert alert-success">Reply-To E-mail: ' . liUser() . '@' . liDomain() . '</div>';
	
	echo '<div class="alert alert-info">Regarding Item: ' . $item . '</div><br />';
	
	echo '<form action="contactSeller.php" method="post">';
	
	echo '<textarea class="form-control" name="contents" rows="6" cols="37">Email Contents Here</textarea><br />';
	
	echo '<input type="hidden" name="sellerEmail2" value="' . $email . '">';
	echo '<input type="hidden" name="item" value="' . $item . '">';
	
        return_home();
        
	echo '<input class="btn btn-primary btn-md" type="submit" value="Contact User!" />';
        
} else if(isset($_POST['sellerEmail2'])){
	$email = $_POST['sellerEmail2'];
	$item = $_POST['item'];
	
	$contents = $_POST['contents'];
	
	$liFullname = liFullname();
	
	Log::logAction($liFullname, 'contacted user ' . $email . ' with the message "' . $_POST['contents'] . '"');
	
	$contents = 'CampusSwap.net! - You have been contacted by ' . liFullname() . ' about your item "' . $item . '" with this message "' . $contents . '""';
	
	echo '<div class="alert alert-success">You have contacted the user about ' . $item . ', he may reply to ' . liFullname() . ' at his convenience</div>';
	
        return_home();
        
	$headers = 'From: doNotReply@campusswap.com' . "\r\n" . 'Reply-To: ' . liFullname();
	
	$subject = 'You have been contacted on Campusswap.com about ' . $item;
	
	mail($email, $subject, $contents, $headers);
	
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
	echo '<div class="alert alert-danger">You do not have persmission to be on this page, your ip ' . $ip . ' has been logged</div>';
	Log::logAction($ip, 'unauthorized access to modifyitem.php page');
}

?>

<?php include('./theme/subpage_foot.php'); ?>