
<?php

//Post Object
$Post = $posts[$x];

$id = $Post->getId();
$username = $Post->getUsername();
$domain = $Post->getDomain();
$item = $Post->getItem();
$descriptionTemp = $Post->getDescription();
$price = $Post->getPrice();
$hits = $Post->getHits();
$views = $Post->getViews();
$img = $Post->getImg();
$modified = $Post->getModified();
$created = $Post->getCreated();
$createdSince = $Post->getCreatedSince(); //TODO: Delete this in the DB;

if(Helper::getDevice()=='tablet'){
    $description = substr($descriptionTemp, 0, 80); //SHORTEN DESCRIPTION 
} else {
    $description = substr($descriptionTemp, 0, 150); //SHORTEN DESCRIPTION 
}
	
$imgSet = false;

if($switch==1) { 
    $switch=0; echo '<div class="result box" id="result">'; 
} else{ 
    $switch = 1; echo '<div class="result" style"background-color:#F8F5F1" id="result">'; 
}
?>
	
<a href="#" 
	style="word-spacing:2px;font-size:small" 
	onclick="
            Effect.toggle('<?php echo 'ROW-' . $id; ?>', 'slide');
            viewItem(<?= $id ?>);
            return false;">
            <?= $item . ' - $' . $price; ?>
	
<?php 
	//ECHO HITS
	if($hits != 0){
		echo '(' . $hits . ' likes)';
	}
	
 	//ECHO SHORTER DESCRIPTION
	if(Helper::getDevice()=='mobile'){ }
	else {
 		if($img!='FALSE' || $img == null){ 
			echo '<i class="icon-film"></i>'; 
			$imgSet = true;
			}
		echo '<small> - ' . $description . '</small>';
	} 
	?>
	</a>

<div class="post_title_name">

<?php 
date_default_timezone_set('America/New_York'); //SET TIME TO DELETION DATES

// TODAYS DATE
$date_created = new DateTime($created);
$todaysDate = date('d.m.y');
$today = new dateTime($todaysDate);
$rightNow = new DateTime(date('Y-m-d H:i:s'));

// DELETE DATE
$delete_date = new DateTime($created);
$delete_date->modify("+60 day");
$time_till_delete = $today->diff($delete_date);

// CREATED SINCE
$created_since = $date_created->diff($rightNow);
$sinceMinutes = $created_since->format('%i');
$sinceSeconds = $created_since->format('%s');
$sinceHours = $created_since->format('%h');
$sinceDays = $created_since->format('%d');

echo '<strong class="muted">';
//if($sinceMinutes >= 1){
//	if($sinceHours >=1){
//		if($sinceDays >= 1){
//			if($sinceDays>1){
//				echo $sinceDays . ' days ago - ';
//			} else {
//				echo $sinceDays . ' day ago - ';
//			}
//		} else {
//			if($sinceHours > 1){
//				echo $sinceHours . ' hours ago - ';
//			} else {
//				echo $sinceHours . ' hour ago - ';
//			}
//			
//		}
//	} else {
//		if($sinceMinutes > 1){
//			echo $sinceMinutes . ' minutes ago - ';
//		} else {
//			echo $sinceMinutes . ' minute ago - ';
//		}
//		
//	}
//} else {
//	if($sinceSeconds > 1){
//		echo $sinceSeconds . ' seconds ago - ';
//	} else {
//		echo $sinceSeconds . ' second ago - ';
//	}
//	
//}
echo '</strong>';

//username mask v**@hartford.edu
$strlen = strlen($username);
$nameEx = str_split($username);

//Date Created
if(Helper::getDevice()!='mobile'){

	echo $nameEx[0];

	for($i = 0; $i < ($strlen - 1); $i++){ //ECHO USERNAME, WITH ***'S
		echo '*';
	} 
		echo '@' . $domain;
}
?>
	
</div>

</div>

<?php
//TODO: Finish if single item
//if(item()=='empty'){ //FINISH IF SINGLE ITEM SELECTED,CHOOSE THIS
//	$postDisplay = '';
//} else {
    $postDisplay = 'display:none;';
//}

?>


<div class="post" id="<?php echo 'ROW-' . $id; ?>" style="margin-bottom:5px;<?= $postDisplay ?>">
  <div>
    <?= $description ?><br />&nbsp;<br />
	<?php if(!Parser::isFalse($img)){ ?>
            <a href="<?= URL ?>var/uploads/<?= $img?>"
               class="lightwindow">
            <img class="post_img"
            align="left" width="100" height="100" src="<?= URL ?>var/uploads/<?= $img ?>" />
            </a>
        <?php //TODO: Bug, the lightwindow image takes too long to load. possibly cache, or pre-load maybe ?>
            <br />
	<?php } ?>
	
	<div>
            <?php
            echo $sinceDays . ' days ago - ';
            echo $sinceHours . ' hours ago - ';
            echo $sinceMinutes . ' minutes ago - ';
            echo $sinceSeconds . ' seconds ago ';
            ?>
        </div>
        <div>
        <?php ////TODO: Sanitize this ?>
        <i class="icon-globe"></i>&nbsp;<?= URL . '?item=' . $id ?><br />&nbsp; 
        Views: <?= $views ?> / 
        Created: <?= $date_created->format('Y-m-d'); ?> / 
        Deletion Date <?= $delete_date->format('Y-m-d') ?> /
        Days till Deletion <?php echo $time_till_delete->format('%R%a days') ?>
		
    <?php if(Authentication::isLi()){ ?>
        <table>
            <tr>
                <td><form id="contactForm" action="contactSeller.php" method="post">
                    <input type="hidden" name="sellerEmail" value="<?= $username ?>@<?= $domain ?>">
                    <input type="hidden" name="item" value="<?= $item?>" />
                    <button type="submit" class="btn btn-small"><i class="icon-comment"></i>Contact Seller</button>
                </form></td>
            <tr>
        <?php if($thisUser->doesUserLike($hits)){ ?><?php //TODO: The like button does not work ?>
            <p class="btn btn-large">You liked this</p>
        <?php } else {?>
            <div action="#" style="padding:none;margin:none;" name="likeButton<?= $id ?>" id="likeButton<?= $id ?>">
                <form id="likeForm" onsubmit="return likeItem(<?= $id ?>); return false;">
                        <button type="submit" class="btn btn-small"><i class="icon-star"></i>Like It</button>
                </form>
            </div>
        <?php } ?>
        </td>
        <td>
            <form id="abuseForm" action="reportAbuse.php" method="post">
                <input type="hidden" name="abuser" value="<?= $username ?>@<?= $domain ?>">
                <input type="hidden" name="reporter" value="<?= liFullname() ?>" />
                <input type="hidden" name="post" value="<?= URL . '?item=' . $id ?>" />
                <input type="hidden" name="abuse" value="abuse" />
                <button type="submit" class="btn btn-small"><i class="icon-flag"></i>Report Abuse</button>
            </form>
        </td>
        </tr>
        </table>
        <?php } else {?>
                <br />
            <form action="login.php">
                <button type="submit" class="btn">Log-in to contact User</button>
            </form>
        <?php } ?>
	</div>
  </div>
</div>

