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

<?php //POST TITLE ?>
    <a href="#"
	style="word-spacing:2px;font-size:small" 
	onclick="
        Effect.toggle('<?php echo 'ROW-' . $id; ?>', 'slide');
        viewItem(<?= $id ?>);
        return false;">
        <?= $item . ' - $' . $price; ?>

    <?php
        //Print days since created badge
    if($sinceDays > 9) {
        echo '<span class="label label-danger">' . $sinceDays . ' days ago</span>';
    } else if($sinceDays > 5) {
        echo '<span class="label label-warning">' . $sinceDays . ' days ago</span>';
    } else if($sinceDays > 3) {
        echo '<span class="label label-primary">' . $sinceDays . ' days ago</span>';
    } else if($sinceDays > 1) {
        echo '<span class="label label-success">' .  $sinceDays. ' days ago</span>';
    } else if($sinceDays == 1) {
        echo '<span class="label label-success">Today</span>';
    }

    ?>

    <?php
	//PRINT HITS
	if($hits != 0){
        if($hits > 6) {
        } else if($hits > 5) {
            echo '<span class="label label-success">' . $hits . ' Likes</span>';
        } else if($hits > 1) {
            echo '<span class="label label-primary">' .  $hits. ' Likes</span>';
        } else if($hits == 1) {
            echo '<span class="label label-warning">' . $hits . ' Like</span>';
        }
	}
	
 	//ECHO SHORTER DESCRIPTION
	if(Helper::getDevice()!='mobile') {
 		if(!Parser::isFalse($img) || $img == null){
			echo '&nbsp;<i class="fa fa-picture-o fa-lg"></i>';
			$imgSet = true;
        }
	}
	?>
</a>

    <?php
    //Obfuscate Username + Print
    $obfuscated_username = Helper::obfuscate_username($username, $domain);
    if(Helper::getDevice()!='mobile'){
        echo '<div class="post_title_name">';
        echo $obfuscated_username;
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
            <h3>
                <?php if(!Parser::isFalse($img)){ //IMAGE ?>
                    <a href="<?= URL ?>var/uploads/<?= $img?>"
                       class="lightwindow">
                        <img class="thumbnail post-img"
                             align="left" width="75" height="65" src="<?= URL ?>var/uploads/<?= $img ?>" />
                    </a>
                    <br />
                <?php } ?>

                <?= $item ?>&nbsp;<small><i class="fa fa-usd"></i><?= $price ?></small></h3>
        </div>

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

        <?php if(1==2) { ?>
        <div class="input-group col-xs-6 col-md-6 col-sm-6">
            <span class="input-group-addon"><i class="fa fa-link"></i></span>
            <input disabled type="text" class="form-control" placeholder="">
        </div><br>
        <?php } ?>

        <ol style="text-align:center" class="breadcrumb">
            <?php $view_feature = false; //TODO: Write the VIEWS feature
            if($view_feature) { ?>
                <li> <b>Views:</b> <?= $views ?> </li>
            <?php } else if(Helper::getDevice()!='mobile'){ ?>
                <li><i class="fa fa-user"></i>&nbsp;<b>User:</b> <?= $obfuscated_username ?>
            <?php } ?>
            <li><i class="fa fa-calendar-o fa-lg"></i>&nbsp;<b>Created:</b> <?= $date_created->format('Y-m-d H:i:s'); ?> </li>
            <?php $del_date_feature = false; //TODO: Write the VIEWS feature
            if($del_date_feature) { ?>
                <li><i class="fa fa-calendar fa-lg"></i>&nbsp;<b>Deletion Date:</b> <?= $delete_date->format('Y-m-d') ?> </li>
            <?php } ?>
            <li><i class="fa fa-bullhorn fa-lg"></i>&nbsp;<b>Days till Deletion:</b> <?php echo str_replace("+", "", $time_till_delete->format('%R%a days')); ?> </li>
            <li><i class="fa fa-link fa-lg"></i>&nbsp;<b>Direct Link:</b> <?= URL . '?item=' . $id ?></li>
        </ol>

        <?php if($isLi){ ?>
            <div class="btn-group btn-group-justified">
                <div class="btn-group">
                    <?php $contact_url = 'approach=' . urlencode("email") . '&sellerEmail=' . urlencode($full_name) . '&id=' . urlencode($id); ?>
                    <a class="btn btn-default lightwindow"
                       role="button"
                       href="<?= URL ?>modules/contact_seller.php?<?= $contact_url ?>"
                       params="lightwindow_type=external,lightwindow_width=527,lightwindow_height=573">
                        <i class="fa fa-comment fa-lg"></i>&nbsp;
                        Contact Seller
                    </a>
                </div>

                <div class="btn-group">
                    <div action="#" style="padding:none;margin:none;" name="likeButton<?= $id ?>" id="likeButton<?= $id ?>">
                        <form id="likeForm" onsubmit="return likeItem(<?= $id ?>); return false;">
                            <?php if($this_user->doesUserLike($id)){ ?>
                                <button disabled type="submit" class="btn btn-default" role="button"><i class="fa fa-star fa-lg"></i>&nbsp;You liked this
                            <?php } else {?>
                                <button type="submit" class="btn btn-default" role="button"><i class="fa fa-star fa-lg"></i>&nbsp;Like It
                            <?php } ?>
                        </button>
                        </form>
                    </div>

                    <div action="#" style="padding:none;margin:none;display:none" name="you_like_<?= $id ?>" id="you_like_<?= $id ?>">
                        <form id="likeForm" onsubmit="return likeItem(<?= $id ?>); return false;">
                            <button disabled type="submit" class="btn btn-default" role="button"><i class="fa fa-star fa-lg"></i>&nbsp;You liked this
                                </button>
                        </form>
                    </div>
                </div>

                <div class="btn-group">
                    <form id="abuseForm" action="<?= URL ?>modules/report_abuse.php" method="post">
                        <input type="hidden" name="abuser" value="<?= $username ?>@<?= $domain ?>">
                        <input type="hidden" name="reporter" value="<?= AuthenticationDAO::liFullName() ?>">
                        <input type="hidden" name="post" value="<?= URL . '?item=' . $id ?>">
                        <input type="hidden" name="abuse" value="abuse" />
                        <button type="submit" class="btn btn-default" role="button"><i class="fa fa-gavel fa-lg"></i>&nbsp;Report Abuse</button>
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

