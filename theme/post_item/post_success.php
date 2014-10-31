<?php

    echo '<div class="alert alert-success">';
    echo 'Your item has been created successfully!</div><br />';
    echo '<b>' . $item . '</b> - ' . $description . '<i>' . $price . '</i><br /><br />';
    echo '<center><img width="200" src="' . URL . $new_file_name . '" /></center><br /><br />';
    return_home_button();

?>