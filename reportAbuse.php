<html>
<head>
<link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<img style="margin-left:42%; text-align:center" src="./img/logo_txt_only.jpg" />
<div style="text-align:center;background-color:#d0ddcf;border: 1px solid #9CAA9C;width:300px;margin-left:30%;width:600px">



<?php

include('./functions.php');



if(isset($_POST['abuse'])){
	
	$post = $_POST['post'];
	$reporter = $_POST['reporter'];
	$abuser = $_POST['abuser'];
	
	
	Log::logAction($abuser, ' has been reported for abuse about ' . $post . ' by ' . $reporter);
	
	echo $abuser . ' has been reported for abuse about <a href="' . $post . '">http://localhost:8888/campusswap/?item=90</a> by ' . $reporter;
}

?>


</div>

<a style="margin-left:48%;text-align:center;color:black" href="<?= Config::get('url'); ?>">Return Home</a>

</html>