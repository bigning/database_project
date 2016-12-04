<?php
// return uesr_id, user_name, user_profile, user_icon
//
error_reporting(-1);

session_start();
$user_id = $_SESSION["user_name"];
$user_name = $_SESSION["user_name"];
$user_profile = $_SESSION["user_profile"];
$user_icon = $_SESSION["user_icon"];

?>



<!-- test -->
<?php 
if ($user_icon === null){
	echo "no picture";
}
?>
	
<!-- homepage FE -->
<?php require "./include/partials/navHeader.php" ?>
<p class="navbar-text navbar-right">
	<?php 
		echo $user_name;
		echo '<img src="' . $user_icon . '">';
	?>

</p>
<img src="">


<?php require "./include/partials/navFooter.php" ?>



<link rel="stylesheet" type="text/css" href="./include/css/homePage.css">
<?php require "./include/partials/footer.php" ?>
