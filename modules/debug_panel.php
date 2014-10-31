<?php if(1==0){ //DEBUGGING PANEL ?>
        <div style="color:black;border: 1px solid #9CAA9C; margin-top:0px;">
            <b>DEBUG:</b> - 
            <?php echo 'college (' . $college . ') - sort (' . $sort . ') - search (' . $search . ') - LoggedIn (' . $loggedIn . ')'; ?>
            <?php echo Authentication::liUser() . '@' . Authentication::liDomain() . ' - ID(' . Authentication::liId() . ')'; ?>
            <?php echo ' - SQL(' . $sql . ')' . ' - searchParts(**' . ')'; ?>
            <?php //echo ' - ' . $_SERVER['REMOTE_ADDR']; ?>
        </div>
<?php } ?>