
<br />

<?php if(!isset($simple)) { ?>
</div>
<?php } ?>

<?php
if(isset($config) && Parser::isTrue(Config::get('debug'))) {
    if(Config::get('debug_location') == 'foot'){
        $subpage = true;
        include DIR . 'modules/debug.php';
    }
}
?>


</body>

</html>