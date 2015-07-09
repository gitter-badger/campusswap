    <div class="foot">
        <center>
            CampusSwap.net &copy; Version Beta <?= $version ?>
            - <a style="color:black" href="<?= $url ?>interface/disclaimer.php">Disclaimer</a>
            - <a style="color:black" href="<?= $url ?>contact.php">Contact</a>
        </center>
    </div>
</div>
</body>
</html>

<?php
    if($debug && $debug_location == 'foot'){
        include DIR . 'modules/debug.php';
    }
?>