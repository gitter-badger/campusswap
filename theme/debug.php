<?php

echo '<div class="debug">';
echo '<b>SQL:</b> ' . $PostsDAO::$sql;
echo "    <br /><b> # Rows: </b>" . $PostsDAO::$row_count . " ";
echo "<b>Search:</b> " . $search_string . " <b>College:</b> " . $college_string . " <b>Sort</b> " . $sort_string . " ";
echo "<b>Pages:</b> " . $pages;
echo '</div>';
?>