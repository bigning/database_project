<?php require "./include/partials/navHeader.php" ?>

<?php 
$login_error = false;
if (array_key_exists("source", $_GET)) {
    if ($_GET["source"] == "login_error") {
        $login_error = true;
    }
}
?>

<!-- login fail $login_error = true -->

<form class = "navbar-form navbar-right" method = "GET" action = "./login.php">
	<div class = "form-group">
		<?php 
			if ($login_error){
				echo "<h3>login error</h3>";
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

<div>
	<h1>Register</h1>
	<form method = "POST" action = "./register.php" enctype="multipart/form-data">
		<input  type="text" name="username" placeholder="User Name" required>
		<input  type="password" name="password" placeholder="Password" required>
		<textarea  name = "description" placeholder="Describe youself"></textarea>
		<label for = "icon">Icon</label>
		<input type="file" name="icon" id="icon">
		<button type="submit" class="btn btn-default">Register</button>
	</form>
</div>


<link rel="stylesheet" type="text/css" href="./include/css/welcomePage.css">
<?php require "./include/partials/footer.php" ?>
