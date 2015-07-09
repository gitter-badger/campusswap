<?php

include('../lib/Config.php');

$Config = new Config('../etc/config.ini');

$dir = Config::get('dir');
if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url');
if(!defined('url')) { define ('URL', $url); }
$enviorment = Config::get('enviorment');

include($dir . 'lib/DAO/PostsDAO.php');
include($dir . 'lib/Util/LogUtil.php');
include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Util/Helper.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');

$database = new Database();
$Conn = $database->connection();
$LogUtil = new LogUtil($Conn, $Config);
$PostsDAO = new PostsDAO($Conn, $Config, $LogUtil);

$error = false;
$image_created = false;
$post_result = false;
define('Megabyte', 1048576);
define('FileUploadLimit', 1.3); //File Upload Limit in MB

//TODO: Filter Post title, price and description but allow the use
// of 1 or 2 Style tags. Probably wont be HTML styling but we will allow some BB style or something


include($dir . 'interface/subpage_head.php');

if(isset($_POST['postItem']) && AuthenticationDAO::isLi()){
    $item = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description']; 
    $username = $_POST['user'];
    $domain = $_POST['domain'];

    try {
        //IF IMAGES UPLOAD ATTEMPTED THEN POST ITEM
        if (isset($_FILES["file"]["size"]) && $_FILES["file"]["size"] > 0) {
            if ($_FILES["file"]["error"] > 0 
                || !isset($_FILES['file']['error'])
                || is_array($_FILES['file']['error']))
            {
                switch ($_FILES['file']['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new RuntimeException('No file sent.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new RuntimeException('Exceeded filesize limit.');
                    default:
                        throw new RuntimeException('Unknown errors.');
                }
                echo "<div class='alert alert-danger'>Your image could not be uploaded, invalid image parameters";
                echo "<br />Error: " . $_FILES["file"]["error"] . "</div>";
                $image = 0;
                $error = TRUE;
            }
            //FILE SIZE
            $image_resize_feature = false;
            if (($_FILES["file"]["size"] > (1*Megabyte)) && $image_resize_feature) { //TODO: Nail out resize
                $img = $_FILES["file"]["size"];

//                $imageResized = Helper::resizeImgage($img , $dest, 480, 400);
    //                if (!move_uploaded_file($_FILES['file']['tmp_name'], $new_file_name )) {
    //                    throw new RuntimeException('Failed to move uploaded file.'); //Could not move file
    //                $image = $_FILES["file"]["tmp_name"];
    //                $new_image = "thumbnails_" . $_FILES["file"]["name"];
    //                copy($_FILES, DIR . "var/uploads/" . $_FILES["file"]["name"]);
    //                $width = 500; //*** Fix Width & Heigt (Autu caculate) ***//
    //                $size = GetimageSize($image);
    //                $height = round($width * $size[1] / $size[0]);
    //                $images_orig = ImageCreateFromJPEG($image);
    //
    //                $photoX = ImagesX($images_orig);
    //                $photoY = ImagesY($images_orig);
    //
    //                $images_fin = ImageCreateTrueColor($width, $height);
    //                ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
    //                ImageJPEG($images_fin, DIR . "var/uploads/" . $new_image);
    //                ImageDestroy($images_orig);
    //                ImageDestroy($images_fin);
    //                $post_result = $PostsDAO->createPost($item, $description, $username, $domain, $price, basename($new_file_name), $Conn);
    //                $image_created = true;
                $image_resize_feature = false;
                throw new RuntimeException("Your image was too large. Please re-size it to under 1.2mb");
            } else if($_POST['resize-image-action']) {

            }
            $file_name = $_FILES["file"]["name"];
            $allowedExts = array("jpg", "jpeg", "gif", "png");
            $explode = explode(".", $file_name);
            $extension = end($explode);

            //CORRECT EXTENSION
            if((strcasecmp($_FILES["file"]["type"],"image/gif")) // TODO: Duplicate code of a ton of crap that can be done in a UITL
                || strcasecmp($_FILES["file"]["type"] == "image/jpeg")
                || strcasecmp($_FILES["file"]["type"] == "image/jpg")
                || strcasecmp($_FILES["file"]["type"] == "image/pjpeg")
                || strcasecmp($_FILES["file"]["type"] == "image/x-png")
                || strcasecmp($_FILES["file"]["type"] == "image/png") 
                || in_array($extension, $allowedExts))
            {

                $new_file_name = sprintf( DIR . 'var/uploads/%s.%s', sha1_file($_FILES['file']['tmp_name']), $extension);
                //Create Post
                if (!move_uploaded_file($_FILES['file']['tmp_name'], $new_file_name )) {
                    throw new RuntimeException('Failed to move uploaded file.'); //Could not move file
                } else {
                    $post_result = $PostsDAO->createPost($item, $description, $username, $domain, $price, basename($new_file_name), $Conn);
                    $image_created = true;
                    if(!$post_result) {
                        throw new Exception("SQL Error creating image post - PostsDAO->createPost(), Post SQL: " . $PostsDAO->postSql);
                    }
                    if($post_result){
                        echo '<div class="alert alert-success">';
                        echo '<h1>Item Posted</h1>';
                        echo '<h5>';
                        echo '<img align=center" href=' . URL . 'var/uploads/' . basename($new_file_name);
                        echo 'Your item has been created successfully!</div><br />';
                        echo '<b>' . $item . '</b> - ' . $description . '<i> - ' . $price . '</i><br /><br />';
                        echo '<center><img width="200" src="' . Config::get('url') . 'var/uploads/' . basename($new_file_name) . '" /></center><br /><br />';
                        echo '</h5>';
                        Helper::return_home_button();
                    }
                }
            } else {
                throw new RuntimeException('Incorrect file type. Only JPG JPEG PNG and GIF images are allowed');
            }
            } else if(!isset($_POST["file"])){ 
                $post_result = $PostsDAO->createPost($item, $description, $username, $domain, $price, "false", $Conn);

                if($post_result){
                    //TODO: Finish these success and failure pages
                    echo '<center>This' . Helper::print_message("Your item was posted succesfully</div>") . '</center>';
                } else {
                    $LogUtil->log(AuthenticationDAO::liFullName(), "error", "PostDAO Error creating Post");
                    Helper::print_error("There was an error posting your item</div>");
                }
                Helper::return_home_button();
            } 
        } catch (Exception $e){
            $LogUtil->log(AuthenticationDAO::liFullName(), "error", $e->getMessage, $e);
            if($enviorment != 'prod') {
                Helper::print_error_dump($e);
            } else {
                Helper::print_error("<b>There was an error posting your item :</b>");
            }
        }
} else if(isset($_POST['imageresize'])){
    //RESIZE IMAGES
    
    $item = $_POST['item'];
    $price = $_POST['price'];
    $description = $_POST['description']; 
    $username = $_POST['username'];
    $domain = $_POST['domain'];

    $allowedExts = array("jpg", "jpeg", "gif", "png");
    $extension = end(explode(".", $_FILES["file"]["name"]));

    //CORRECT EXTENSION
    if((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
            && in_array($extension, $allowedExts)){

        $new_file_name = sprintf( DIR . 'var/uploads/%s.%s', sha1_file($_FILES['file']['tmp_name']), $extension);

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $new_file_name)) {
            throw new RuntimeException('Failed to move uploaded file.');
        } else {
            
            $image = $_FILES["file"]["tmp_name"];
            $new_image = "thumbnails_".$_FILES["file"]["name"];
            copy($_FILES,DIR . "var/uploads/".$_FILES["file"]["name"]);
            $width = 500; //*** Fix Width & Heigh (Autu caculate) ***//
            $size = GetimageSize($image);
            $height = round($width*$size[1]/$size[0]);
            $images_orig = ImageCreateFromJPEG($image);

            $photoX = ImagesX($images_orig);
            $photoY = ImagesY($images_orig);

            $images_fin = ImageCreateTrueColor($width, $height);
            ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
            ImageJPEG($images_fin, DIR . "var/uploads/".$new_image);
            ImageDestroy($images_orig);
            ImageDestroy($images_fin);

            $post_result = $PostsDAO->createPost($item, $description, $username, $domain, $price, basename($new_file_name), $Conn);
            $image_created = true;

            if($post_result){
                include DIR . 'interface/post_item/post_success.php';
            }
        }
    } else {
        echo '<div class="alert alert-danger">Incorrect file type. Only JPG JPEG PNG and GIF images are allowed</div>';
        Helper::return_home_button();
    }
} else {
    echo 'You do not have permission to be on this page, your IP has been logged';
    $LogUtil->log('IP', 'ACTION', 'Unauthorized Access Post_Item.php', 'unauthorized access');
}


?>

<?php 
include($dir . 'interface/subpage_foot.php');
?>