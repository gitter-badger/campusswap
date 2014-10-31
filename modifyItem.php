<?php

include('./theme/subpage_head.php');

include($dir . 'lib/DAO/PostsDAO.php');

include('./functions.php');
include('./lib/Domains.php');
include('./lib/Users.php');
include('./lib/vers.php');
include('./lib/Posts.php');
include('./lib/Database.php');


$database = new Database();
$conn = $database->connection();


if(isset($_POST['edit']) && isLi()){
	
	$id = $_POST['id'];

    $post = new PostsDAO($conn);
        
	echo '
            <center><img width="100" src="<?= URL ?>var/uploads/' . $post->getImg() . '" /></center>
            <br />
            <form name="postItem" action="modifyItem.php" method="post">
            <b>Item Name</b><input class="tall_text_box input-group input-group-lg" type="text" name="item" value="' . $post->getItem() . '" /><br />
            <b>Item Price</b><input class="tall_text_box input-group input-group-lg" type="text" name="price" value="' . $post->getPrice() . '" /><br />
            <b>Item Description</b><textarea class="form-control" name="description" rows="6" cols="80">' . $post->getDescription() . '</textarea><br />

            <input type="hidden" name="id" value="' . $id . '">
            <input type="hidden" name="edit2" value="true">
            <input type="submit" class="btn btn-primary" value="Submit" />
            </form>
	';
	
	
} else if(isset($_POST['edit2'])){
	$id = $_POST['id'];
	$item = $_POST['item'];
	$description = $_POST['description'];
	$price = $_POST['price'];
	
	//$sql = mysql_query("UPDATE posts SET item='$item' WHERE id='$id'");
	//$sql = mysql_query("UPDATE posts SET description='$description' WHERE id='$id'");
	//$sql = mysql_query("UPDATE posts SET price='$price' WHERE id='$id'");
        
        $PostsDAO = new PostsDAO($conn);
        
        $id_update = $PostsDAO::updatePosts('item', $item, $id, $conn);
        $desc_update = $PostsDAO::updatePosts('description', $description, $id, $conn);
        $price_update = $PostsDAO::updatePosts('price', $price, $id, $conn);
        
        if($id_update || $desc_update || $price_update){
            echo '<div class="alert alert-success">Post successfully updated updated</div><br />';
            echo $item . '<br />';
            echo $description . '<br />';
            echo $price . '<br />';
            return_home();
        } else {
            echo '<div class="alert alert-danger">Post was not updated succesfully, we have logged this error apoligize for any inconvenience. If this problem persists, contact us.</div><br />';
            echo '<a class="btn btn-primary" href="/contact.php">Contact Us</a>';
            return_home();
            
            
        }
	
	echo 'Post Updated!';
	
} else if(isset($_POST['delete'])){
	$id = $_POST['id'];
	
	$sql = mysql_query("SELECT item, description, price FROM posts WHERE id = '$id'");
	$row = mysql_fetch_array($sql);
	
	echo 'Are you sure you will like to delete the post titled <b>"' . $row['item'] . '"</b>';
	echo '
	<form name="delete2" action="modifyItem.php" method="post">
	<input type="hidden" name="id" value="' . $id . '">
	<input type="hidden" name="delete2" value="true">
	<input type="submit" value="Yes" />
	';
	echo 'Or <a href="' . Config::get('url') . '">Bring Me Home</a>';
	
	
} else if(isset($_POST['delete2'])){
	$id = $_POST['id'];
	
	$sql = mysql_query("DELETE FROM posts WHERE id='$id'");
	
	echo 'Item Deleted!';
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
	echo 'You do not have permission to be on this page, your ip ' . $ip . ' has been logged';
	Log::logAction($ip, 'unauthorized access to modifyitem.php page');
}
?>

<?php include('./theme/subpage_foot.php'); ?>