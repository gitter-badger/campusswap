<?php
include('./adminHead.php');


if(isset($_POST['submitted'])){
	
	$i1 = $_POST['item1'];
	$p1 = $_POST['price1'];
	$de1 = $_POST['description1'];
	$u1 = $_POST['username1'];
	$d1 = $_POST['domain1'];
	
	$sql = mysql_query("INSERT INTO posts (item, description, username, domain, price, hits, views, created, modified) VALUES ('$i1', '$de1', '$u1', '$d1', '$p1', '0', '0', NOW(), NOW())");
	
	
	if($_POST['use2']=='yes'){
		
		$i2 = $_POST['item2'];
		$p2 = $_POST['price2'];
		$de2 = $_POST['description2'];
		$u2 = $_POST['username2'];
		$d2 = $_POST['domain2'];

		$sql = mysql_query("INSERT INTO posts (item, description, username, domain, price, hits, views, created, modified) VALUES ('$i2', '$de2', '$u2', '$d2', '$p2', '0', '0', NOW(), NOW())");
		
	}
	
	if($_POST['use3']=='yes'){
		
		$i3 = $_POST['item3'];
		$p3 = $_POST['price3'];
		$de3 = $_POST['description3'];
		$u3 = $_POST['username3'];
		$d3 = $_POST['domain3'];

		$sql = mysql_query("INSERT INTO posts (item, description, username, domain, price, hits, views, created, modified) VALUES ('$i3', '$de3', '$u3', '$d3', '$p3', '0', '0', NOW(), NOW())");
		
	}
	
	if($_POST['use4']=='yes'){
		
		$i4 = $_POST['item4'];
		$p4 = $_POST['price4'];
		$de4 = $_POST['description4'];
		$u4 = $_POST['username4'];
		$d4 = $_POST['domain4'];

		$sql = mysql_query("INSERT INTO posts (item, description, username, domain, price, hits, views, created, modified) VALUES ('$i4', '$de4', '$u4', '$d4', '$p4', '0', '0', NOW(), NOW())");
	}
	
	if($_POST['use5']=='yes'){
		
		$i5 = $_POST['item5'];
		$p5 = $_POST['price5'];
		$de5 = $_POST['description5'];
		$u5 = $_POST['username5'];
		$d5 = $_POST['domain5'];

		$sql = mysql_query("INSERT INTO posts (item, description, username, domain, price, hits, views, created, modified) VALUES ('$i5', '$de5', '$u5', '$d5', '$p5', '0', '0', NOW(), NOW())");
	}
	
} else {

echo '<h1>Add 5 Posts at a time</h1>'; ?>

<form action="add_posts_batch.php" method="POST">

<table>
	<tr>
		<td></td>
		<td>Username</td>
		<td>Domain</td>
		<td>Item</td>
		<td>Price</td>
		<td>Description</td>
	</tr>
	<tr>
		<td>Required</td>
		<td><input type="text" name="username1" /></td>
		<td><input type="text" name="domain1" /></td>
		<td><input type="text" name="item1" /></td>
		<td><input type="text" name="price1" /></td>
		<td><textarea name="description1"></textarea></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="use2" value="yes"/></td>
		<td><input type="text" name="username2" /></td>
		<td><input type="text" name="domain2" /></td>
		<td><input type="text" name="item2" /></td>
		<td><input type="text" name="price2" /></td>
		<td><textarea name="description2"></textarea></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="use3" value="yes" /></td>
		<td><input type="text" name="username3" /></td>
		<td><input type="text" name="domain3" /></td>
		<td><input type="text" name="item3" /></td>
		<td><input type="text" name="price3" /></td>
		<td><textarea name="description3"></textarea></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="use4" value="yes" /></td>
		<td><input type="text" name="username4" /></td>
		<td><input type="text" name="domain4" /></td>
		<td><input type="text" name="item4" /></td>
		<td><input type="text" name="price4" /></td>
		<td><textarea name="description4"></textarea></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="use5" value="yes" /></td>
		<td><input type="text" name="username5" /></td>
		<td><input type="text" name="domain5" /></td>
		<td><input type="text" name="item5" /></td>
		<td><input type="text" name="price5" /></td>
		<td><textarea name="description5"></textarea></td>
	</tr>
</table>
	<input type="hidden" name="submitted">
	<input type="submit" value="submit" />
</form>



<?php
}

include('./adminFoot.php');

?>