<html>
<head>
<link rel="stylesheet" type="text/css" href="<?= URL ?>css/bootstrap3.css" />
<link rel="stylesheet" type="text/css" href="<?= URL ?>css/newStyle.css" />
<link rel="stylesheet" type="text/css" href="<?= URL ?>css/style.css" />

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

<div class="subpage_container">
<center>
    <a href="/">
        <img src="<?= URL ?>/img/campusSwapLogoOnly.jpg" />
    </a>
</center>
    
<br />
<br />