<?php 

// include_once 'connection.php';
include_once 'class_lib.php';
session_start();

if (isset($_POST) and !empty($_POST))
{
	if ($_POST['action'] == "login")
	{
		login();
	}
	else if ($_POST['action'] == "register")
	{
		register();
	}
	else if ($_POST['action'] == "add_friend")
	{
		add_friend();
	}
}
// LOG OUT
else
{
	session_destroy();
	header("Location: index.php");
}


function login()
{
	$user = new User();
	$errors = "";
	$password = md5($_POST['password']);

	$errors .= Validation::email($_POST['email']);
	$errors .= Validation::password($password);

	if (empty($errors))
	{
		$errors .= $user->retrieve_existing_user($_POST['email'], $password);

		// FOUND USER if no errors are returned
		if (empty($errors))
		{
			$_SESSION['logged_in'] = true;
			$_SESSION['user']['id'] = $user->get_id();
			$_SESSION['user']['first_name'] = $user->get_first_name();
			$_SESSION['user']['last_name'] = $user->get_last_name();
			$_SESSION['user']['email'] = $user->get_email();

			header("Location: profile.php");
		}
		// NO USER if errors are returns
		else
		{
			$errors .= "Invalid login info<br />";
			$_SESSION['login_errors'] = $errors;

			header("Location: index.php");
		}
	}
	else
	{
		$_SESSION['login_errors'] = $errors;

		header("Location: index.php");
	}
}

function register()
{
	$user = new User();
	$errors = "";
	$password = md5($_POST['password']);

	$errors .= Validation::email($_POST['email']);
	$errors .= Validation::password($password);
	$errors .= $user->exists_already($_POST['email']);

	if (empty($errors))
	{
		$user->create_new_user($_POST['first_name'], $_POST['last_name'], $_POST['email'], $password);
		$_SESSION['logged_in'] = true;
		$_SESSION['user']['id'] = $user->get_id();
		$_SESSION['user']['first_name'] = $user->get_first_name();
		$_SESSION['user']['last_name'] = $user->get_last_name();
		$_SESSION['user']['email'] = $user->get_email();

		header("Location: profile.php");
	}
	else
	{
		$_SESSION['register_errors'] = $errors;

		header("Location: index.php");
	}
}

function add_friend()
{
	$friend = new Friend();

	$friend->make_friend($_SESSION['user']['id'], $_POST['friend_id']);

	header("Location: profile.php");
}

?>