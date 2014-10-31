<?php

include('../lib/Config.php');

$config = new Config('../etc/config.ini');

$dir = Config::get('dir');
if(!defined('dir')) { define ('DIR', $dir); }
$url = Config::get('url');
if(!defined('url')) { define ('URL', $url); }

include($dir . 'lib/DAO/PostsDAO.php');

include($dir . 'lib/Util/Parser.php');
include($dir . 'lib/Helper.php');
include($dir . 'lib/Domains.php');
include($dir . 'lib/Users.php');
include($dir . 'lib/vers.php');
include($dir . 'lib/Posts.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/Authentication.php');
include($dir . 'lib/log4php/Logger.php');
include($dir . 'lib/Mobile_Detect.php');

include($dir . 'theme/subpage_head.php');

$database = new Database();
$conn = $database->connection();

$error = false;
$image_created = false;
$post_result = false;

if(isset($_POST['postItem']) && isLi()){
	
    $item = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description']; 
    $username = $_POST['user'];
    $domain = $_POST['domain'];

    //IF IMAGES UPLOAD ATTEMPTED THEN POST ITEM
    if (isset($_FILES["file"]["size"]) && $_FILES["file"]["size"] > 0) {
        try {
            if ($_FILES["file"]["error"] > 0 
            || !isset($_FILES['file']['error'])
            || is_array($_FILES['file']['error'])){

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
            if (($_FILES["file"]["size"] < 1000000)) { //TODO: Nail out file size + test

                $file_name = $_FILES["file"]["name"];
                
                $allowedExts = array("jpg", "jpeg", "gif", "png");
                $explode = explode(".", $file_name);
                $extension = end($explode);

                //CORRECT EXTENSION
                if((($_FILES["file"]["type"] == "image/gif") // TODO: Duplicate code of a ton of crap that can be done in a UITL
                    || ($_FILES["file"]["type"] == "image/jpeg")
                    || ($_FILES["file"]["type"] == "image/jpg")
                    || ($_FILES["file"]["type"] == "image/pjpeg")
                    || ($_FILES["file"]["type"] == "image/x-png")
                    || ($_FILES["file"]["type"] == "image/png"))
                    && in_array($extension, $allowedExts)){

                    //TODO: Double check the new Uploads URL
                    $new_file_name = sprintf(DIR . 'var/uploads/%s.%s', sha1_file($_FILES['file']['tmp_name']), $extension);

                    if (!move_uploaded_file($_FILES['file']['tmp_name'], $new_file_name )) {
                        throw new RuntimeException('Failed to move uploaded file.'); //Could not move file
                    } else { //Create Post
                        $post_result = $PostsDAO->createPost($item, $description, $username, $domain, $price, basename($new_file_name), $conn);
                        $image_created = true;
                        
                        if($post_result){
                            echo '<div class="alert alert-success">';
                            echo 'Your item has been created successfully!</div><br />';
                            echo '<b>' . $item . '</b> - ' . $description . '<i>' . $price . '</i><br /><br />';
                            echo '<center><img width="200" src="' . Config::get('url') . $new_file_name . '" /></center><br /><br />';
                            return_home_button();
                        }
                    }
                } else {
                    throw new RuntimeException('Incorrect file type. Only JPG JPEG PNG and GIF images are allowed');
                }
            } else {
                echo '<div class="alert alert-danger">Your image is too large, would you like to re-size it?</div>';
                echo '<form method="POST" action="postItem.php">';
                echo '<input type="hidden" name="imageresize" value="imageresize">';
                echo '<input type="hidden" name="item" value="' . $item . '">';
                echo '<input type="hidden" name="price" value="' . $price . '">';
                echo '<input type="hidden" name="description" value="' . $description . '">';
                echo '<input type="hidden" name="user" value="' . $username . '">';
                echo '<input type="hidden" name="domain" value="' . $domain . '">';
                echo '<input type="submit" class="btn btn-success">Re-Size Image Automatically</input>';
                return_home_button();
                echo '</form>';
            }
        } catch (RuntimeException $e){
            echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
            }
    } else if(!isset($_POST["file"])){ 
        $post_result = $PostsDAO->createPost($item, $description, $username, $domain, $price, false, $conn);
        
        if($post_result){
            //TODO: Finish these success and failure pages
            echo '<div class="alert alert-success">Your item was posted succesfully</div>';
        } else {
            echo '<div class="alert alert-danger">There was an error posting your item</div>';
        }
        return_home_button();
    } 
} else if(isset($_POST['imageresize'])){
    //RESIZE IMAGES
    
    $item = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description']; 
    $username = $_POST['user'];
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
            ImageJPEG($images_fin,DIR . "var/uploads/".$new_image);
            ImageDestroy($images_orig);
            ImageDestroy($images_fin);

            $post_result = $PostsDAO->createPost($item, $description, $username, $domain, $price, basename($new_file_name), $conn);
            $image_created = true;

            if($post_result){
                include DIR . 'theme/post_item/post_success.php';
            }
        }
    } else {
        echo '<div class="alert alert-danger">Incorrect file type. Only JPG JPEG PNG and GIF images are allowed</div>';
        return_home_button();
    }
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
    echo 'You do not have permission to be on this page, your IP has been logged';
    Log::logAction($ip, 'unauthorized access to the postItem.php page');
}


?>

<?php 
include($dir . 'theme/subpage_foot.php'); 


/*
   DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
   Check MIME Type by yourself.
  $finfo = new finfo(FILEINFO_MIME_TYPE);

  if (false === $ext = array_search(
      $finfo->file($_FILES['upfile']['tmp_name']),
      array(
          'jpg' => 'image/jpeg',
          'png' => 'image/png',
          'gif' => 'image/gif',
      ),
      true
  )) {
      throw new RuntimeException('Invalid file format.');
  }
  */


?>