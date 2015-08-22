
<?php 
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Campus Swap </title> <?php //TODO: Add title ?>

    <script type="text/javascript">
        var cswap_debug = <?= $Parser->getBoolean($debug); ?>;
        var cswap_pages = <?= $Parser->getNumber($pages) ?>;
        var cswap_total = <?= $Parser->getNumber($PostsDAO->total_count) ?>;
        var cswap_college = '<?= $Parser->getBoolean($college) ?>';
        var cswap_sort = '<?= $Parser->getBoolean($sort) ?>';
        var cswap_search = '<?= $Parser->getBoolean($search) ?>';
        var cswap_first = 40;
        var cswap_url = '<?= URL ?>';
    </script>

    <?php //todo: move these to the footer for faster page + optimize + test speed ?>
    <script src="<?= URL ?>interface/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?= URL ?>interface/js/prototype.js" type="text/javascript"></script>
    <script src="<?= URL ?>interface/js/scriptaculous.js" type="text/javascript"></script>
    <script src="<?= URL ?>interface/js/lightwindow.min.js" type="text/javascript"></script>
    <script src="<?= URL ?>interface/js/bootstrap.min.js" type="text/javascript"></script>
	
    <link href="<?= URL ?>interface/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= URL ?>interface/css/lightwindow.css" rel="stylesheet">
    <link href="<?= URL ?>interface/css/style.css" rel="stylesheet" >
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<!--    <link href="--><?//= URL ?><!--interface/css/font-awesome.min.css" rel="stylesheet">-->

    <script type="text/javascript">
        jQuery.noConflict();
    </script>
    
    <script src="<?= URL ?>interface/js/campusswap_infinite_scroll.js" type="text/javascript"></script>
    <!-- //TODO: Fix the post price form validation -->
    <script src="<?= URL ?>interface/js/campusswap_core.js" type="text/javascript"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<?php	
$display = 'display:none';
if(!$AuthenticationDAO->isLi()) { //COOKIE FIRST VISIT WELCOME MSG?>
    <?php if(isset($_COOKIE['firstVisit'])){} else {}  //TODO: If we can, then make this responsive
}
if($Helper->getDevice()=='mobile'){
	$span2 = ' span2';
} else {
	$span2 = '';
}
?>

<?php include DIR . 'interface/index/user_manager_navbar.php'; ?>

<div class="container-fluid"> <!-- Main Container -->

	<a href="<?= URL ?>"><img src="<?= URL ?>interface/img/logo.png" /></a>
	
	<?php
	if($Helper->getDevice()=='mobile'){
        //TODO: Let's make this crap responsive
		echo '<div class="top box span2"style="margin-bottom:10px">';
	} else {
		echo '<div class="top box"style="margin-bottom:10px">';
	}
	?>

	<?php include DIR . 'modules/breadcrumbs.php'; ?>
</div>


	
