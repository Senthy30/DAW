<?php session_start(); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
	</head>

	<body>

		<?php if(isset($_SESSION['id']) && isset($_SESSION['email'])) { ?>

			<h1>Hello, <?php echo $_SESSION['fname']; ?>!</h1>
			<a href="login/logout.php">Logout</a>

		<?php } else { ?>

			<h1>Hello, Guest!</h1>

			<a href="login/index.php">Log in</a> <br>
			<a href="register/index.php">Create an account</a>

		<?php } ?>

	</body>
</html>