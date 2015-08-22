<?php

include('adminHead.php');
	
	echo '<h1>Posts</h1>';
	
	echo '<form action="./Posts.php" method="GET">';
	echo '<input type="text" name="search" value="search" />';
	echo '</form><br />';
	
	$resultFull = mysql_query("SELECT * FROM posts");
	$resultCount = mysql_num_rows($resultFull);
	$pageCount = ceil($resultCount / 40);
	
	$sql = "SELECT * FROM posts";
	
	if(isset($_GET['search'])){
		
		$search = AddSlashes($_GET['search']);
		
		$sql = $sql . " WHERE item LIKE '%$search%' OR description LIKE '%$search%'";
	}
	
	if(isset($_GET['page'])){
		if($_GET['page']==1){
			$page = 1;
			$sql = $sql . " LIMIT 0 , 40";
		} else {
			$page = $_GET['page'];
			$first = ($page * 40) - 40;
			$sql = $sql . " LIMIT " . $first . " , 40";
		}
	} else {
		$page = 1;
		$sql = $sql . " LIMIT 0 , 40";
	}
	
	echo '<h1>Page ' . $page . '</h1>';
	
	$query = mysql_query($sql);
	
	echo '<table class="table table-striped table-bordered table-condensed">';
	
	echo '<tr>';
	echo '<td>id</td><td>Item Name</td><td>Item Description</td><td>delete</td></tr>';
	
	while($row = mysql_fetch_assoc($query)){
		
		$description = substr($row['description'], 0, 250) . $Config->get('url') . '?item=' . $row['id'];
		echo '<tr>';
		
		echo '<td>' . $row['id'] . '</td><td>' . $row['item'] . '</td><td>' . $description . '</td>';
		echo '<td>';
		/*echo '<form action="./deletePost.php" method ="POST">';
		echo '<input type="hidden" value="' . $row['id'] . '" name="id" />';
		echo '<input type="submit" value="Delete" /></form>';
		*/
		echo '</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	
	
	
	echo '<h2>';
	for($i = 1; $i <= $pageCount; $i++){
		if($i == $page){ echo '<i><a href="' . $Config->get('url') . 'admin/Posts.php?page=' . $i . '">' . $i . ' - </a></i>'; }
		else { echo '<a href="' . $Config->get('url') . 'admin/Posts.php?page=' . $i . '">' . $i . ' - </a>';}
		
	}
	echo '</h2>';
	
include('adminFoot.php');
?>