<?php require "./include/partials/navHeader.php" ?>

<form class = "navbar-form navbar-right" mehtod = "GET" action = "./login.php">
	<div class = "form-group">
		<input class="form-control" type="text" name="username" placeholder="User Name" required>
		<input class="form-control" type="password" name="password" placeholder="Password" required>
	</div>
	<button type="submit" class="btn btn-default">Login</button>
</form>

<?php require "./include/partials/navFooter.php" ?>



<div>
	<h1>Register</h1>
	<form mehtod = "POST" action = "./register.php">
		<div class = "form-group">
			<label for = "username">Username</label>
			<input type="text" name="username" id = "username" required>
			<label for = "password">Password</label>
			<input type="password" name="password" id = "password" required>
			<label for = "description">Description</label>
			<textarea id = "description" name = "description"></textarea>
			<label for = "icon">Icon</label>
			<input type="file" name="icon">
		</div>
		<input type="submit">
	</form>
</div>


<?php require "./include/partials/footer.php" ?>
