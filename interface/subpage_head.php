<?php

use Parser;
use Config;

if($Parser->isTrue($Config->get('debug'))) {
    if($Config->get('debug_location') === 'foot'){
        $subpage = true;
        include DIR . 'modules/debug.php';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Campus Swap</title>

    <script src="<?= URL ?>interface/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?= URL ?>interface/js/prototype.js" type="text/javascript"></script>
    <script src="<?= URL ?>interface/js/scriptaculous.js" type="text/javascript"></script>
    <script src="<?= URL ?>interface/js/lightwindow.min.js" type="text/javascript"></script>

    <link href="<?= URL ?>interface/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?= URL ?>interface/css/lightwindow.css" rel="stylesheet" media="screen">
    <link href="<?= URL ?>interface/css/style.css" rel="stylesheet" >
    <link href="<?= URL ?>interface/css/font-awesome.min.css" rel="stylesheet">

    <script type="text/javascript">
    function matchPass(){
        var password1=document.forms["passChoose"]["password1"].value;
        var password2=document.forms["passChoose"]["password2"].value;

        if(password1!=password2){
            alert("Your password don't match");
            return false;
        }

        if(password1.length < 4 || password1.length > 20){
            alert("Your password must be between 4 and 20 characters long");
            return false;
        }

        if(password2.length < 4 || password2.length > 20){
            alert("Your password must be between 4 and 20 characters long");
            return false;
        }

    }
    </script>

<?php include DIR . 'modules/current_page.php'; ?>

</head>



<?php if(!isset($simple)) { ?>
<div class="subpage_container">
<center>
    <a href="<?= URL ?>">
        <img src="<?= URL ?>/interface/img/logo.png" />
    </a>
</center>
<br />
<br />
<?php } ?>
    
