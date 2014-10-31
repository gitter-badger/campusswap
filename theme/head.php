
<?php 
error_reporting(E_ALL);

require_once('functions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php
    if(Helper::getDevice()=='mobile'){
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    }
    ?>
    <title>Campus Swap </title> <?php //TODO: Add title ?>
	
    <link href="<?= URL ?>theme/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="<?= URL ?>theme/css/bootstrap3_forms_glyph.css" rel="stylesheet" media="screen">
    <link href="<?= URL ?>theme/css/lightwindow.css" rel="stylesheet" media="screen">
    <!-- <link href="./css/screen.css" rel="stylesheet" media="screen"> -->
    <link type="text/css" href="<?= URL ?>theme/css/style.css" rel="stylesheet"  >

    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>    
    <script src="<?= URL ?>js/prototype.js" type="text/javascript"></script>
    <script src="<?= URL ?>js/scriptaculous.js" type="text/javascript"></script>
    <script src="<?= URL ?>js/lightwindow_compressed.js" type="text/javascript"></script>

    <script type="text/javascript"> <?PHP //TODO: Move this javascript out and oraganize JS folder ?>

    jQuery.noConflict();

    var pages = <?= $pages ?>;
    var total = <?= $post_count ?>;
    var college = <?= $college_string ?>;
    var sort = <?= $sort_string ?>;
    var $search = <?= $search_string ?>;

    </script>
    
    <script src="<?= URL ?>js/campusswap_infinite_scroll.js" type="text/javascript"></script>
    
    <script src="<?= URL ?>js/campusswap_core.js" type="text/javascript"></script>

</head>
<body>
	
<?php include DIR . 'modules/debug_panel.php'; ?>

<div class="loginBar" name="loginbar"> <?PHP //LOGIN BAR ?>
    <?php 
    if($loggedIn){ ?>
        <a href="<?= URL ?>theme/user_posts.php" params="lightwindow_type=external"  class="lightwindow">
        <?php 
             echo '<i class="icon-user"></i>&nbsp;' . $liUser . '@' . $liDomain . '</a>';
        if($liLevel=='admin'){
            echo '<a href="admin/index.php"> / <i class="icon-wrench"></i>&nbsp; Admin';
        }
        echo ' / <a href="logout.php"><i class="icon-road"></i>&nbsp;Logout</a></b>';
    } else { ?>
        <a href="#" onclick="Effect.toggle('login_register', 'appear'); return false;"><i class="icon-lock"></i><b>&nbsp;Login/ Register</b></a>
    <?php } ?>
        </a>
</div>
<?php	
$display = 'display:none';
if(!Authentication::isLi()) { //COOKIE FIRST VISIT ?> 
    <?php if(isset($_COOKIE['firstVisit'])){
        //DO NOTHING
    } else {
        //DO NOTHING
    }
}
if(Helper::getDevice()=='mobile'){
	$span2 = ' span2';
} else {
	$span2 = '';
}
?>
<div id="login_register" class="loginTop<?= $span2 ?>" style="<?= $display ?>"> <?PHP //WELCOME - LOGIN - SIGNUP ?>
    <?php //LOGIN ?>
      <div class="login_register_inner row-fluid">
            <div class="login_section span6">
                <h3 class="muted">Login</h3>
                <form name="input" action="<?= URL ?>login.php" method="post">
                    <input class="tall_text_box input-group input-group-md" type="text" value="College E-Mail Address" name="username" />
                    <select class="form-control input-md" name="domain">
                        <?php
                        foreach($domains->getColleges() as $login_domain_rows){
                            echo '<option value = "' . $login_domain_rows['domain'] . '">@' . $login_domain_rows['domain'] . '</option>';
                        }
                        ?>
                    </select><br />
                    <input class="tall_text_box form-control input-md" type="password" size="30" name="password" value="password" />
                    <input type="hidden" name="loginSubmitted" value="TRUE">
                    <input class="btn btn-primary btn-md btn-block" type="submit" class="btn" value="Login" />
                    <a href="<?= URL ?>recoverPassword.php">Recover Password</a>
                </form>
            </div>
          <?php //REGISTER ?>
            <div class="register_section span5">
                <h3 class="muted">Register<small>(Available Upon Release)</small></h3>
                <form name="input" action="<?= URL ?>signup.php" method="post">
                    <input class="tall_text_box input-group input-group-md" type="text" value="College E-Mail Address" name="username" />
                    <select class="form-control input-md" name="domain">
                        <?php

                        foreach($domains->getColleges() as $register_domain_rows){
                                echo '<option value = "' . $register_domain_rows['domain'] . '">@' . $register_domain_rows['domain'] . '</option>';
                        }

                        ?>
                    </select><br />
                    <input type="hidden" name="signup" value="TRUE">
                    <input class="btn btn-primary btn-md btn-block" type="submit"  value="One Click Signup! (No BS)" />
                </form>
            </div>
    </div>
</div> <?PHP //END WELCOME - LOGIN - SIGNUP ?>

<?php if(Authentication::isLi()){ //USER POSTS IFRAME ?>

<div class="container-fluid" id="userPosts" style="display:none; border-bottom:1px solid grey">
	<iframe seamless="seamless" frameborder="0" src="<?= DIR ?>userPosts.php" width="1200" height="100"></iframe>	
</div>
<?php }

	echo '<div class="container-fluid">';
	?>
	<a href="<?= URL ?>"><img src="<?= URL ?>img/campusSwap.jpg" /></a>
	
	<?php
	if(Helper::getDevice()=='mobile'){
		echo '<div class="top box span2"style="margin-bottom:10px">';
	} else {
		echo '<div class="top box"style="margin-bottom:10px">';
	}
	?>

	<b><a href="<?= URL ?>">Home</a></b>
	
	<?php 
        //TODO: Implement buttons here, with the post count badges and x buttons to close. 
        // A new button for each query entity: search, college and sort
        // http://getbootstrap.com/components/#badges
	if($college == 'all'){
            echo '<b> > <a href="' . Config::get('url') . '"> All Colleges</a></b>';
            $title = 'All Colleges - <font class="muted">Most Recent</font>';
            if($search){
                echo '<b> > Search "' . $search . '"</b>';
                echo  '<a href="' . URL . '"> <small>(clear search)</small></a>';
                $title = $title . '<font class="muted"> - Search: "' . $search . '"</font>';
            }
	} else { 
            echo '<b> > <a href="' . URL . '?college=' . $college . '">' . Domains::getCollegeName($college) . '</a> </b>';
            $title = Domains::getCollegeName($college);

            if(!Parser::isFalse($search)){
                echo '<b> > Search "' . $search . '"</b>';
                echo  '<b><a href="' . URL . '?college=' . $college . '"> <small>(clear search)</small></a></b>';
                $title = $title . '<font class="muted"> - Search: "' . $search . '"</font>';
            }
            if(!Parser::isFalse($sort)){
                echo '<b>';
                $navSort = $PostsDAO->getSortText($sort);
                $navSort = ' > ' . $navSort;

                echo $navSort;

                $title = $title . ' <font class="muted">' . $navSort . '</font>';
                echo '</b>';
            }
	}
	 
	if($item){
            echo '<b> > Item id ' . $item . '</b>';
	} else if($post_count > 0){
            echo '<b> > ' . $post_count . ' items found</b>';
	} else {
            echo '<b> > 0 items found </b>';
	}
	?>
</div>


	
