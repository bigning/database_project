<?php require "./include/partials/navHeader.php" ?>

<form class = "navbar-form navbar-right" method = "GET" action = "./login.php">
	<div class = "form-group">
		<input class="form-control" type="text" name="username" placeholder="User Name" required>
		<input class="form-control" type="password" name="password" placeholder="Password" required>
	</div>
	<button type="submit" class="btn btn-default">Login</button>
</form>

<?php require "./include/partials/navFooter.php" ?>

<div>
	<h1>Register</h1>
	<form method = "POST" action = "./register.php">
		<input  type="text" name="username" placeholder="User Name" required>
		<input  type="password" name="password" placeholder="Password" required>
		<textarea  name = "description" placeholder="Describe youself"></textarea>
		<label for = "icon">Icon</label>
		<input type="file" name="icon">
		<button type="submit" class="btn btn-default">Register</button>
	</form>
</div>

<link rel="stylesheet" type="text/css" href="./include/css/welcomePage.css">
<?php require "./include/partials/footer.php" ?>
