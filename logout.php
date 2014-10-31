<?php


include('./theme/subpage_head.php');

include('./functions.php');
include('./lib/Domains.php');
include('./lib/Users.php');
include('./lib/vers.php');
include('./lib/Posts.php');
include('./lib/Database.php');

$database = new Database();
$conn = $database->connection();

unset($_SESSION['user']);
unset($_SESSION['domain']);
unset($_SESSION['userId']);
unset($_SESSION['level']);

session_destroy();
session_unset();

?>
<br />

    <div class="alert alert-success">You have successfully logged out!</div>
     <?php return_home(); ?>

<?php include('./theme/subpage_foot.php'); ?>