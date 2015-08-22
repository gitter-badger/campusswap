<?php

include('./adminHead.php');

if($isLi && $isAdmin) {

    echo '<h1>Welcome Admin</h1>';

    echo '<form action="makePost.php" method="GET" />';
    echo '<input type="text" value="count" name="count" />';
    echo '<input type="submit" value="submit" />';

	
} else {
    $AccessDAO->addIntrusion();
    $LogUtil->log('IP', 'action', 'intrusion');
}
include('./adminFoot.php');



?>