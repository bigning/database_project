<?php
// return uesr_id, user_name, user_profile, user_icon
//
error_reporting(-1);

session_start();
$user_id = $_SESSION["user_name"];
$user_name = $_SESSION["user_name"];
$user_profile = $_SESSION["user_profile"];
$user_icon = $_SESSION["user_icon"];

echo $user_name;
?>















<!-- homepage FE -->
<?php require "./include/partials/navHeader.php" ?>

<?php require "./include/partials/navFooter.php" ?>

<?php require "./include/partials/footer.php" ?>
