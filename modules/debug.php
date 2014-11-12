<?php

echo '<div class="debug">';

echo '<b>DEBUG</b><br>';
if(isset($PostsDAO) && !isset($subpage)) {
    echo '<b>SQL:</b> ' . $PostsDAO::$sql;

    echo "<br /><b> FP Rows: </b>" . Parser::getNumber(PostsDAO::$fp_count) . " ";
    echo "<b>Total Rows: </b>" . Parser::getNumber(PostsDAO::$total_count) . " ";

    echo "<b>Search:</b> " . Parser::getString($search) . " <b>College:</b> " . Parser::getString($college) . " <b>Sort</b> " . Parser::getString($sort) . " ";

    echo "<b>Pages:</b> " . Parser::getNumber($pages);
}

if($_POST != null) {
    echo "<b>POST Variable</b>: <br>";
    echo "(key - value)<br>";
    foreach ($_POST as $key => $value) {
        echo "(" . $key . " - " . $value . ")<br>";
    }
}

echo "<br>";
if($_GET != null) {
    echo "<b>GET Variable</b>: <br>";
    echo "(key - value)<br>";
    foreach ($_GET as $key => $value) {
        echo "(" . $key . " - " . $value . ")<br>";
    }
}

echo '<br>';
if(isset($AuthenticationDAO)) {
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
}



echo '</div>';
?>