<?php

echo '<div class="debug">';

echo '<br><div class="alert alert-warning">';

echo '<b>DEBUG</b>';
//if(isset($PostsDAO) && !isset($subpage)) {
//    echo '<b>SQL:</b> ' . $PostsDAO->sql;
//
//    echo "<br /><b> FP Rows: </b>" . $Parser->getNumber($PostsDAO->fp_count) . " ";
//    echo "<b>Total Rows: </b>" . $Parser->getNumber($PostsDAO->total_count) . " ";
//
//    echo "<b>Search:</b> " . $Parser->getBoolean($search) . " <b>College:</b> " . $Parser->getBoolean($college) . " <b>Sort</b> " . $Parser->getBoolean($sort) . " ";
//
//    echo "<b>Pages:</b> " . $Parser->getNumber($pages);
//}

if($_POST != null) {
    echo "<b>POST Variable</b>: <br>";
//    echo "(key - value)<br>";
    foreach ($_POST as $key => $value) {
        echo "(" . $key . " - " . $value . ")<br>";
    }
}


if($_GET != null) {
    echo "<br>";
    echo "<b>GET Variable</b>: <br>";
//    echo "(key - value)<br>";
    foreach ($_GET as $key => $value) {
        echo "" . $key . " - " . $value . "<br>";
    }
}

echo '<br>';
if(isset($AuthenticationDAO)) {
    echo '<b><u>Authentication</u> - </b> ';
    $auth = $AuthenticationDAO->getAuthObject();
    $liUser = $auth->getLiUser();
    $isLi = $auth->getIsLi();
    $liDomain = $auth->getLiDomain();
    $liId = $auth->getLiId();
    $liLevel = $auth->getLiLevel();
    $liFullName = $auth->getLiFullName();

    echo "<b>User:</b> " . $liUser . " ";
    echo "<b>Authenticated:</b> " . $isLi . " ";
    echo "<b>Domain:</b> " . $liDomain . " ";
    echo "<b>Id:</b> " . $liId . " ";
    echo "<b>Level:</b> " . $liLevel . " ";
    echo "<b>FullName:</b> " . $liFullName;
    echo '<br>';
}

if(isset($VersDAO)) {
    echo '<b>Vers</b>';
    echo '<b>SQL</b>: ' . $VersDAO::$sql;
    echo '<br>';
}

//echo '<b>PostsDAO Status:</b> ' . $Parser->getBoolean($PostsDAO) . ' ';

if(isset($subpage)) {
    if($subpage != FALSE) {
        echo '<b>Subpage Status:</b> ' . $Parser->getBoolean($subpage) . ' ';
    } else {
        echo '<b>Subpage Status:</b> Not False (TRUE)';
    }
} else {
    echo '<b>Subpage Status:</b> Not Set';
}
echo '<br><br>';

if(isset($PostsDAO)) {
    if($PostsDAO != FALSE) {
        if($subpage != TRUE) {
            echo '<b><u>PostsDAO TRUE</u> - </b> ';
            echo '<b>Front Page Rows: </b>' . $PostsDAO->fp_count . ' ';
            echo '<br>';
            echo '<b>Full SQL: </b>' . $PostsDAO->sql . '<br> ';
            echo '<b>Limit SQL: </b>' . $PostsDAO->limit_sql . '<br> ';
            echo "<b>Total Rows: </b>" . $Parser->getNumber($PostsDAO->total_count) . " ";
            echo "<b>Search:</b> " . $Parser->getBoolean($search) . " <b>College:</b> " . $Parser->getBoolean($college) . " <b>Sort</b> " . $Parser->getBoolean($sort) . " ";
            echo "<b>Pages:</b> " . $Parser->getNumber($pages);
        } else {
            echo '<b>Subpage Status:</b> True ';
        }
    } else {
        echo '<b>PostsDAO Boolean:</b> False';
    }
} else {
    echo '<b>PostsDAO:</b> Not Set';
}


echo '</div></div>';
?>