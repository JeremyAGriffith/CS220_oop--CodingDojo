<?php 
	include_once 'connection.php';
	session_start();

	if (!isset($_SESSION['logged_in']))
	{
		header("Location: index.php");
	}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>OOP - Advanced "Profile"</title>
	<!-- jQuery -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

	<!-- my css -->
	<!-- <link rel="stylesheet" href="css.css"> -->

	<!-- inline scripts -->
	<script typ="text/javacsript">
		$(document).ready(function(){
			// AJAX
			// $("form").on('submit', function(){
			// 	console.log($(this).serialize());
			// 	$.post(
			// 		$(this).attr('action'), 
			// 		$(this).serialize());
			// 		// function(data){
			// 			// $("#country_info").html(data.html);
			// 		// }, "json");

			// 		//return false;
			// });

			$("button").on('click', function(){
				$("#friend_id").val($(this).val());
				console.log($("#friend_id").val());
				console.log($(form.serialize()));
				// $.post(
				// 	$(this).attr('action'), 
				// 	$(this).serialize());
				// $("form").submit();
			});
		});
	</script>
</head>
<body>
	<div id="user_info">
		<?="Welcome, ".$_SESSION['user']['first_name']."!<br />".$_SESSION['user']['email'];?>
		<a href="process.php">Log Off</a>
	</div>
	<div>
		<h2>List of Friends</h2>
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
				</tr>
			</thead>
			<tbody id="friends_list">
<?php 			$connection = new DataBase();
				// $friends_list_query = "SELECT users.id, CONCAT(users.first_name,' ',users.last_name) AS friend_name, 
				// 	users.email FROM friends 
				// 	LEFT JOIN users on friends.friend_id = users.id 
				// 	WHERE friends.user_id = {$_SESSION['user']['id']} OR 
				// 	friends.friend_id = {$_SESSION['user']['id']}";
				// friend relatiship where the current user was the person who friended another user
				$friends_list_query = "SELECT CONCAT(users.first_name,' ',users.last_name) AS friend_name, 
					users.email	FROM friends 
					LEFT JOIN users on friends.friend_id = users.id 
					WHERE friends.user_id = {$_SESSION['user']['id']}";
				$friends = $connection->fetch_all($friends_list_query);
				// friend relationship where the current user was the person friended by someone else
				$friends_list_query = "SELECT CONCAT(users.first_name,' ',users.last_name) AS friend_name, 
					users.email FROM friends 
					LEFT JOIN users on friends.user_id = users.id 
					WHERE friends.friend_id = {$_SESSION['user']['id']}";
				$friends = array_merge($friends, $connection->fetch_all($friends_list_query));

				$html = "";
				foreach ($friends as $friend)
				{
					$html .= "
						<tr>
							<td>{$friend['friend_name']}</td>
							<td>{$friend['email']}</td>
						</tr>";
				}
				echo $html;
?>			</tbody>
		</table>				
	</div>
	<div id="all_users_list">
		<h2>List of Users who subscribed to Friend Finder</h2>
		<form action="process.php" method="post">
			<input type="hidden" name="action" value="add_friend">
			<input id="friend_id" type="hidden" name="friend_id" value="0">
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
<?php 			// $all_users_not_friends_list_query = "SELECT users.id, CONCAT(users.first_name,' ',users.last_name) AS user_name, 
					// users.email, friends.user_id, 
					// GROUP_CONCAT(DISTINCT friends.friend_id)
					// FROM users 
					// left join friends on friends.user_id = users.id 
					// where users.id != {$_SESSION['user']['id']} and (friends.friend_id NOT LIKE '%{$_SESSION['user']['id']}%' or friends.friend_id is null)
					// group by users.id";

				// $all_users_not_friends_list_query = "SELECT users.id, CONCAT(users.first_name,' ',users.last_name) AS user_name, 
				// 	users.email FROM users 
				// 	LEFT JOIN friends on friends.user_id = users.id 
				// 	WHERE users.id != 1 AND (friends.friend_id != 1 OR friends.friend_id IS NULL)";
				// $not_friends = $connection->fetch_all($all_users_not_friends_list_query);
				
				// get all users except current logged in user
				$current_user_id = $_SESSION['user']['id'];
				$all_users_query = "SELECT id, CONCAT(first_name,' ',last_name) AS user_name, 
					email FROM users WHERE id != {$current_user_id}";
				$all_users = $connection->fetch_all($all_users_query);

				$html = "";

				foreach ($all_users as $user)
				{
					$html .= "
						<tr>
							<td>{$user['user_name']}</td>
							<td>{$user['email']}</td>";
					// determine if user is a friend of the current logged in user
					$is_friend_query = "SELECT * FROM friends 
						WHERE (user_id = {$current_user_id} AND friend_id = {$user['id']}) 
						OR (user_id = {$user['id']} AND friend_id = {$current_user_id})";
					$is_friend = $connection->fetch_record($is_friend_query);
					$html .= $is_friend ? "<td>Friends</td>" : 
						"<td><button value='{$user['id']}'>Add as Friend</button></td>";
					$html .= "</tr>";
				}

				// // NOT FRIENDS
				// foreach ($not_friends as $not_friend)
				// {
				// 	$html .= "
				// 		<tr>
				// 			<td>{$not_friend['user_name']}</td>
				// 			<td>{$not_friend['email']}</td>
				// 			<td><button value='{$not_friend['id']}'>Add as Friend</button></td>
				// 		</tr>";
				// }
				// // FRIENDS
				// foreach ($friends as $friend)
				// {
				// 	$html .= "
				// 		<tr>
				// 			<td>{$friend['friend_name']}</td>
				// 			<td>{$friend['email']}</td>
				// 			<td>Friends</td>
				// 		</tr>";
				// }
				echo $html;
?>					</tr>
				</tbody>
			</table>
		</form>
	</div>
</body>
</html>