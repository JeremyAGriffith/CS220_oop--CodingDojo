<?php 

include_once 'connection.php';

class User
{
	private $data_base;
	private $id;
	private $first_name;
	private $last_name;
	private $email;

	function __construct()
	{
		$this->data_base = new DataBase();
	}

	public function exists_already($email)
	{
		$errors = "";

		$user_exists_query = "SELECT * FROM users WHERE email = '{$email}'";
		$user = $this->data_base->fetch_record($user_exists_query);

		// USER INFO already exists
		if (!empty($user))
		{
			$errors = "someone else is already using tht email address<br />";
		}

		return $errors;
	}
	/*****
	* 
	* run method above first to validate
	* 
	*****/
	public function create_new_user($first_name, $last_name, $email, $password)
	{
		$new_user_query = "INSERT INTO users (first_name, last_name, email, password, created_at) 
			VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$password}', NOW())";
		mysql_query($new_user_query);

		$new_user_id_query = "SELECT id FROM users WHERE email = '{$email}'";
		$id = $this->data_base->fetch_record($new_user_id_query);

		// $_SESSION['logged_in'] = true;
		$this->id = $id['id'];
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;

		// header("Location: profile.php");
	}

	/*****
	* 
	* if successful then 
	* 	return an empty string
	* else return a string containing the errors
	* 
	*****/
	public function retrieve_existing_user($email, $password)
	{
		$errors = "";

		$user_login_query = "SELECT * FROM users 
			WHERE email = '{$email}' AND password = '$password'";
		$user = $this->data_base->fetch_record($user_login_query);

		// FOUND USER
		if (!empty($user))
		{
			// $_SESSION['logged_in'] = true;
			$this->id = $user['id'];
			$this->first_name = $user['first_name'];
			$this->last_name = $user['last_name'];
			$this->email = $user['email'];

			// header("Location: profile.php");
		}
		// NO USER
		else
		{
			$errors = "Invalid login info<br />";
			// $_SESSION['login_errors'] = $errors;
			// header("Location: index.php");
		}

		return $errors;
		// $_SESSION['login_errors'] = $errors;
		// header("Location: index.php");				
	}

	/*****
	* 
	* GETTERS
	* 
	*****/
	public function get_id()
	{
		return $this->id;
	}
	public function get_first_name()
	{
		return $this->first_name;
	}
	public function get_last_name()
	{
		return $this->last_name;
	}
	public function get_email()
	{
		return $this->email;
	}
}

class Friend
{
	private $data_base;

	function __construct()
	{
		$this->data_base = new DataBase();
	}

	public function make_friend($user_id, $friend_id)
	{
		$make_friend_query = "INSERT INTO friends (user_id, friend_id) 
			VALUES ({$user_id}, {$friend_id})";
		mysql_query($make_friend_query);

		// $make_friend_query = "INSERT INTO friends (user_id, friend_id) 
		// 	VALUES ({$friend_id}, {$user_id})";
		// mysql_query($make_friend_query);
	}
}

class Validation
{
	static function name($name)
	{
		$errors = "";

		if(!(isset($name) and !empty($name) and ctype_alpha($name)))
		{
			$errors = "name is not valid!<br />";
		}

		return $errors;
	}

	static function email($email)
	{
		$errors = "";

		if (!(isset($_POST['email']) and filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)))
		{
			$errors = "email is not valid<br />";
		}

		return $errors;
	}

	static function password($password, $conf_password = "", $conf_pass_used = false)
	{
		$errors = "";

		if ($conf_pass_used)
		{
			if(!(isset($password) and strlen($password) >= 6))
			{
				$errors = "please double check your password (length must be greater than 6)<br />";
			}

			if (!(isset($conf_password) and isset($password) and $password == $conf_password))
			{
				$errors .= "passwords do not match, please confirm it<br />";
			}
		}
		else
		{
			if(!(isset($password) and strlen($password) >= 6))
			{
				$errors = "please double check your password (length must be greater than 6)<br />";
			}
		}

		return $errors;
	}
}

?>