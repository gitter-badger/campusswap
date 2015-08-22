<?php
include('./adminHead.php');

if($isLi && $isAdmin) {
    echo '<h1>Access</h1>';

    echo '<table class="table table-striped table-bordered table-condensed">';
        echo '<tr>';
            echo '<td>IP</td>';
            echo '<td>Usernames</td>';
            echo '<td>Statis</td>';
            echo '<td>Visits</td>';
            echo '<td>Intrusions</td>';
            echo '<td>Failed Logins</td>';
            echo '<td>Access Date (24hr period)</td>';
        echo '</tr>';

        while($a = $AccessDAO->getAccesses()){
            echo '<tr>';

            echo '<td>' . $a->getIp() . '</td>';
            echo '<td>' . $a->getUsernames() . '</td>';
            echo '<td>' . $a->getStatus() . '</td>';
            echo '<td>' . $a->getVisits() . '</td>';
            echo '<td>' . $a->getIntrusions() . '</td>';
            echo '<td>' . $a->getFailedLogins() . '</td>';
            echo '<td>' . $a->getDatetime() . '</td>';

            echo '<td><form style="height:9px;" name="banUser" action="banUser.php" method="post"><input type="submit" value="Ban" /></form></td>';

            echo '</tr>';
        }
    echo '</table>';
    
} else {
    $AccessDAO->addIntrusion();
    $LogUtil->log('IP', 'action', 'intrusion - ');
}
include('./adminFoot.php');



?>