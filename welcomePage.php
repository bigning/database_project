<?php require "./include/partials/navHeader.php" ?>

<?php 
$login_error = false;
if (array_key_exists("source", $_GET)) {
    if ($_GET["source"] == "login_error") {
        $login_error = true;
    }
}
?>










<!-- Front End -->

<form class = "navbar-form navbar-right" method = "GET" action = "./login.php">
	<div class = "form-group">
		<?php 
			if ($login_error){
				echo "<script type='text/javascript'>alert('Ooops! Wrong User Name or Password');</script>";
			}
		?>
	</div>
	<div class = "form-group">
		<input class="form-control" type="text" name="username" placeholder="User Name" required>
		<input class="form-control" type="password" name="password" placeholder="Password" required>
	</div>
	<button type="submit" class="btn btn-default">Login</button>
</form>

<?php require "./include/partials/navFooter.php" ?>

	<form id = "register_form" method = "POST" action = "./register.php" enctype="multipart/form-data">
		<div class = "form-group">
			<input  class = "form-control form-input" type="text" name="username" placeholder="User Name" required>
		</div>
		<div class = "form-group">
			<input  class = "form-control form-input" type="password" name="password" placeholder="Password" required>
		</div>
		<div class = "form-group">
			<textarea  class = "form-control" name = "description" placeholder="Describe youself"></textarea>
		</div>	
		<div class = "form-group form-input">
			<span id = "yourIcon">Your Icon</span>
			<input type="file" name="icon" id="icon">
		</div>
		<button id = "registerButton" type="submit" class="btn btn-default btn-lg">Register</button>
	</form>

<link rel="stylesheet" type="text/css" href="./include/css/welcomePage.css">
<script type="text/javascript" src = "./include/framework/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="./include/js/welcomePage.js"></script>
<?php require "./include/partials/footer.php" ?>
