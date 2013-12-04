<?php 
	session_start();

	if (isset($_SESSION['logged_in']))
	{
		header("Location: profile.php");
	}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>OOP - Advanced "Login/Register"</title>
</head>
<body>
	<div id="container">
		<form action="process.php" method="post">
<?php 			if (!empty($_SESSION['register_errors']))
				{
					echo $_SESSION['register_errors'];
					unset($_SESSION['register_errors']);
				}
?>			<input type="hidden" name="action" value="register">
			First Name <input type="text" name="first_name" /><br />
			Last Name <input type="text" name="last_name" /><br />
			Email <input type="text" name="email" /><br />
			Password <input type="password" name="password" /><br />
			<button type="submit">Register</button>
		</form>
		<form action="process.php" method="post">
<?php 			if (!empty($_SESSION['login_errors']))
				{
					echo $_SESSION['login_errors'];
					unset($_SESSION['login_errors']);
				}
?>			<input type="hidden" name="action" value="login">
			Email <input type="text" name="email" /><br />
			Password <input type="password" name="password" /><br />
			<button type="submit">Login</button>
		</form>
	</div>
</body>
</html>