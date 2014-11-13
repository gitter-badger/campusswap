<?php

//Post Object
$Post = $posts[$x];
$id = $Post->getId();
$username = $Post->getUsername();
$domain = $Post->getDomain();
$full_name = $username . "@" . $domain;
$item = $Post->getItem();
$description = $Post->getDescription();
$price = $Post->getPrice();
$hits = $Post->getHits();
$views = $Post->getViews();
$img = $Post->getImg();
$modified = $Post->getModified();
$created = $Post->getCreated();
$createdSince = $Post->getCreatedSince(); //TODO: Delete this in the DB;

if(Helper::getDevice()=='tablet'){
    $descriptionShort = substr($description, 0, 80); //SHORTEN DESCRIPTION
} else {
    $descriptionShort = substr($description, 0, 150); //SHORTEN DESCRIPTION
}

// TODAYS DATE
date_default_timezone_set('America/New_York'); //SET TIME TO DELETION DATES
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

$imgSet = false;

if($switch==1) { 
    $switch=0;
echo '<div class="result box" id="result">';
} else {
    $switch = 1;
    echo '<div class="result" style"background-color:#F8F5F1" id="result">';
}
?>

<?php //POS TITLE ?>
    <a href="#"
	style="word-spacing:2px;font-size:small" 
	onclick="
        Effect.toggle('<?php echo 'ROW-' . $id; ?>', 'slide');
        viewItem(<?= $id ?>);
        return false;">
        <?= $item . ' - $' . $price; ?>

    <?php
    if($sinceDays > 7) {
        echo '<span class="label label-danger">' . $sinceDays . ' days ago</span>';
    } else if($sinceDays > 3) {
        echo '<span class="label label-warning">' . $sinceDays . ' days ago</span>';
    } else if($sinceDays > 1) {
        echo '<span class="label label-primary">' . $sinceDays . ' days ago</span>';
    } else if($sinceDays == 1) {
        echo '<span class="label label-primary">' . $sinceDays . ' day ago</span>';
    } else if($sinceDays == 0) {
        echo '<span class="label label-success">Today</span>';
    }
    ?>

    <?php
	//ECHO HITS
	if($hits != 0){
		echo '<span class="btn">Likes <span class="badge">' . $hits . ' </span></span>)';
	}
	
 	//ECHO SHORTER DESCRIPTION
	if(Helper::getDevice()!='mobile') {
 		if(!Parser::isFalse($img) || $img == null){
			echo '<i class="fa fa-picture-o fa-lg"></i>';
			$imgSet = true;
        }
	}
	?>
</a>

    <?php
        //Obfuscate Username + Print
        if(Helper::getDevice()!='mobile'){
            echo '<div class="post_title_name">';
            echo Helper::obfuscate_username($username, $domain);
            echo '</div>';
        }
    ?>
</div>


<?php
//TODO: Finish if single item
if(isset($$total_count) && $total_count = 1){ //FINISH IF SINGLE ITEM SELECTED,CHOOSE THIS
	$postDisplay = '';
} else {
    $postDisplay = 'display:none;';
}
?>

<?php //POST CONTENTS ?>
<div class="post" id="<?php echo 'ROW-' . $id; ?>" style="margin-bottom:5px;<?= $postDisplay ?>">
    <div class="media">

        <div class="post_description page-header"> <?PHP //TITLE ?>
            <h3><?= $item ?>&nbsp;<small><i class="fa fa-usd"></i><?= $price ?></small></h3>
        </div>

        <?php if(!Parser::isFalse($img)){ //IMAGE ?>
            <a href="<?= URL ?>var/uploads/<?= $img?>"
               class="lightwindow">
                <img class="thumbnail"
                     align="left" width="100" height="90" src="<?= URL ?>var/uploads/<?= $img ?>" />
            </a>
            <br />
        <?php } ?>

        <p><?php echo $description ?></p><br><br><br>

<!--        <div>-->
<!--            --><?php
//            Print the ammount of days since created
//            echo $sinceDays . ' days ago - ';
//            echo $sinceHours . ' hours ago - ';
//            echo $sinceMinutes . ' minutes ago - ';
//            echo $sinceSeconds . ' seconds ago ';
//            ?>
<!--        </div>-->

        <div class="input-group col-xs-4 col-md-4 col-sm-4">
            <span class="input-group-addon"><i class="fa fa-link"></i></span>
            <input disabled type="text" class="form-control" placeholder="<?= URL . '?item=' . $id ?>">
        </div><br>

        <ol class="breadcrumb">
            <li> <b>Views:</b> <?= $views ?> </li>
            <li><i class="fa fa-calendar-o fa-lg"></i> <b>Created:</b> <?= $date_created->format('Y-m-d H:i:s'); ?> </li>
            <li><i class="fa fa-calendar fa-lg"></i> <b>Deletion Date:</b> <?= $delete_date->format('Y-m-d') ?> </li>
            <li><i class="fa fa-bullhorn fa-lg"></i> <b>Days till Deletion:</b> <?php echo str_replace("+", "", $time_till_delete->format('%R%a days')); ?> </li>
        </ol>

        <?php if($isLi){ ?>
            <div class="btn-group btn-group-justified">
                <div class="btn-group">
                    <?php $contact_url = 'approach=' . urlencode("email") . '&sellerEmail=' . urlencode($full_name) . '&id=' . urlencode($id); ?>
                    <a class="btn btn-default lightwindow"
                       role="button"
                       href="<?= URL ?>modules/contact_seller.php?<?= $contact_url ?>"
                       params="lightwindow_type=external,lightwindow_width=527,lightwindow_height=573">
                        <i class="fa fa-comment fa-lg"></i>
                        Contact Seller
                    </a>
                </div>

                <div class="btn-group">
                    <?php if($this_user->doesUserLike($hits)){ ?><?php //TODO: The like button does not work ?>
                        <p class="btn btn-large">You liked this</p>
                    <?php } else {?>
                        <div action="#" style="padding:none;margin:none;" name="likeButton<?= $id ?>" id="likeButton<?= $id ?>">
                            <form id="likeForm" onsubmit="return likeItem(<?= $id ?>); return false;">
                                <button type="submit" class="btn btn-default" role="button"><i class="fa fa-star fa-lg"></i>Like It</button>
                            </form>
                        </div>
                    <?php } ?>
                </div>

                <div class="btn-group">
                    <form id="abuseForm" action="<?= URL ?>modules/report_abuse.php" method="post">
                        <input type="hidden" name="abuser" value="<?= $username ?>@<?= $domain ?>">
                        <input type="hidden" name="reporter" value="<?= AuthenticationDAO::liFullName() ?>">
                        <input type="hidden" name="post" value="<?= URL . '?item=' . $id ?>">
                        <input type="hidden" name="abuse" value="abuse" />
                        <button type="submit" class="btn btn-default" role="button"><i class="fa fa-gavel fa-lg"></i>Report Abuse</button>
                    </form>
                </div>

            </div>

            <?php } else { //Not logged in?>
                    <br />
                <form action="login.php">
                    <button type="submit" class="btn">Log-in to contact User</button>
                </form>
            <?php } ?>
        </div>
  </div>

