<?php require "./include/partials/header.php" ?>

<h1>Login</h1>
<form mehtod = "GET" action = "./login.php">
	<label for = "username">Username</label>
	<input type="text" name="username" id = "username" required>
	<label for = "password">Password</label>
	<input type="password" name="password" id = "password" required>
	<input type="submit">
</form>

<h1>Register</h1>
<form mehtod = "POST" action = "./register.php">
	<label for = "username">Username</label>
	<input type="text" name="username" id = "username" required>
	<label for = "password">Password</label>
	<input type="password" name="password" id = "password" required>
	<br>
	<label for = "description">Description</label>
	<textarea id = "description" name = "description"></textarea>
	<br>
	<label for = "icon">Icon</label>
	<input type="file" name="icon">
	<br>
	<input type="submit">
</form>

<?php require "./include/partials/footer.php" ?>
