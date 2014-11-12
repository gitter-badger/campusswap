<?php
include('../../lib/Config.php');
$config = new Config('../../etc/config.ini');
$dir = Config::get('dir'); if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url'); if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');

$AuthenticationDAO = new AuthenticationDAO($config);
$auth = $AuthenticationDAO->getAuthObject();
$liUser = $auth->getLiUser();
$liDomain = $auth->getLiDomain();
$liId = $auth->getLiId();
$liLevel = $auth->getLiLevel();
$liFullName = $auth->getLiFullName();
$isLi = $auth->getIsLi();

$debug = Parser::isTrue(Config::get('debug'));
$database = new Database();
$conn = $database->connection();
$LogUtil = new LogUtil($conn, $config);
$PostsDAO = new PostsDAO($conn, $config, $LogUtil);
?>

<?php include(DIR . 'interface/subpage_head.php'); ?>

<?php

if(isset($_POST['edit']) && AuthenticationDAO::isLi()){

//    $LogUtil->log($liFullName, "info", "We are loading multiple (" . PostsDAO::$fp_count . ") posts: " . $PostsDAO::$sql);

	$id = $_POST['id'];

    $post = $PostsDAO->getPost($id);

    if(!Parser::isFalse($post->getImg())){
         echo '<img align="center" width="100" src="<?= URL ?>var/uploads/' . $post->getImg() . '" />';
    }

    echo '<br />
        <form name="postItem" action="' . URL . 'interface/user_manager/modify_item.php" method="post">
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
        
    $id_update = $PostsDAO->updatePosts('item', $item, $id);
    $desc_update = $PostsDAO->updatePosts('description', $description, $id);
    $price_update = $PostsDAO->updatePosts('price', $price, $id);

    if($id_update || $desc_update || $price_update){
        echo '<div class="alert alert-success">Post successfully updated updated</div><br />';
        echo $item . '<br />';
        echo $description . '<br />';
        echo $price . '<br />';
        Helper::return_home_button();
    } else {
        echo '<div class="alert alert-danger">Post was not updated succesfully, we are sorry for any inconvenience.
                                                If this problem persists, contact us.</div><br />';
        echo '<a class="btn btn-primary" href="/contact.php">Contact Us</a>';
        Helper::return_home_button();
    }
	echo 'Post Updated!';
} else if(isset($_POST['delete'])){
	$id = $_POST['id'];

    $item = $PostsDAO->getPost($id);
	
	echo 'Are you sure you will like to delete item <b>"' . $item->getItem() . '"</b>';
	echo '
	<form name="delete2" action="modify_item.php" method="post">
	<input type="hidden" name="id" value="' . $id . '">
	<input type="hidden" name="delete2" value="true">
	<input type="submit" value="Yes" />
	';
	echo 'Or <a href="' . Config::get('url') . '">Bring Me Home</a>';
	
	
} else if(isset($_POST['delete2'])){
	$id = $_POST['id'];
	
	$deleted = $PostsDAO->deleteItem($id);

    if($deleted){
        echo 'Item Deleted!';
        $LogUtil->log($liFullName, 'action', 'Deleted Item ' . $id);
    } else {
        echo 'Could not delete Item!';
        $LogUtil->log($liFullName, 'fatal', "Item: " . $id . " could not be deleted");
    }


} else {
	$ip = LogUtil::getIp();
	echo 'You do not have permission to be on this page, your ip ' . $ip . ' has been logged';
    $LogUtil->log($ip, 'action', 'Unauthorized access to modifyitem.php page');
}
?>

<?php include(DIR . 'interface/subpage_foot.php'); ?>