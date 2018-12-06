<?PHP

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../config/connect.php";

if (isset($_SESSION['id']))
	$username = $_SESSION['id'];
if (isset($_SESSION['email']))
	$email = $_SESSION['email'];

$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

//Notifications
if ($_POST["submit"] == "Set Notification Settings") {
	if ($_POST["notifications"] == "Yes")
		$notifications = 1;
	else
		$notifications = 0;
	$stmt = $con->prepare("UPDATE `users` SET `notifications` = :notifications WHERE `username` = :username");
	$stmt->execute(["notifications" => $notifications, "username" => $username]);
	
	if ($stmt->rowCount() > 0)
	{
		$stmt = $con->prepare("SELECT `email` FROM `users` WHERE `username` = :username");
		$stmt->execute(["username" => $username]);
		$email = $stmt->fetch(PDO::FETCH_ASSOC);
		$email = $email["email"];
		$subject = "Notification Settings Change";
		$url = str_replace("backend/change_credentials.php", "frontend/account.php", $url);
		if ($notifications == 0) {
			$body = "<html> <body> <h1> Hi $username! </h1>
				<br/> <h2> You have opted out of receiving email notifications on your posts.
				To reverse this, head back to your <a href='$url'> account page </a> and select 'Yes' in the notifications section. </h2> </body> </html>";
		} else {
			$body = "<html> <body> <h1> Hi $username! </h1>
				<br/> <h2> You have opted in to receiving email notifications on your posts.
				To reverse this, head back to your <a href='$url'> account page </a> and select 'No' in the notifications section. </h2> </body> </html>";
		}
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";	
		mail($email, $subject, $body, $headers);
		echo "<script> alert('You have changed your notifications settings.'); location.href='../frontend/account.php'; </script>";
	}
}

//Reset Password
if ($_POST['submit'] == "Reset")
{
	$oldpw = serialize(hash("whirlpool", $_POST['oldpw']));
	$newpw = serialize(hash("whirlpool", $_POST['newpw']));
	$confirm = serialize(hash("whirlpool", $_POST['confirm']));
	if ($newpw == $confirm)
	{
		$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username AND `passwd` = :passwd");
    	$stmt->execute(['username' => $username, 'passwd' => $oldpw]);

		if ($stmt->rowCount())
		{
			$stmt = $con->prepare("UPDATE `users` SET `passwd` = :newpw WHERE `username` = :username AND `passwd` = :oldpw");
			$stmt->execute(["newpw" => $newpw, "username" => $username, "oldpw" => $oldpw]);
			
			if ($stmt->rowCount() > 0)
			{
				$subject = "Password Reset";
				$body = "<html> <body> <h1> Hi $username! <h2>
						<br/> <h2> This email is just to confirm that your password has been successfully reset. </h2> </body> </html>";
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				mail($email, $subject, $body, $headers);
				echo "<script> alert('Your password has been sucessfully reset.'); location.href='../frontend/account.php'; </script>";
			}
			else
				echo "<script> alert('Error in resetting password. Please try again.'); location.href='../frontend/account.php'; </script>"; 
		}
		else
			echo "<script> alert('Username and password do not match.'); location.href='../frontend/account.php'; </script>";
	}
	else
		echo "<script> alert('Passwords do not match.'); location.href='../frontend/account.php'; </script>";
}

//Update Email
if ($_POST['submit'] == "Update")
{
	$oldem = $_POST['oldem'];
	$newem = $_POST['newem'];
	$confirm = $_POST['confirm'];
	if ($newem == $confirm)
	{
		$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username AND `email` = :email");
    	$stmt->execute(['username' => $username, 'email' => $oldem]);

		if ($stmt->rowCount())
		{
			$statement = $con->prepare("SELECT `email` FROM `users` WHERE `email` = :email");
			$statement->execute(['email' => $newem]);
			if ($statement->rowCount())
				echo "<script> alert('Email address already in use. Please use another.'); location.href='../frontend/account.php'; </script>";
			else
			{
				$stmt = $con->prepare("UPDATE `users` SET email = :newem WHERE username = :username AND `email` = :oldem");
				$stmt->execute(["newem" => $newem, "username" => $username, "oldem" => $oldem]);
			
				if ($stmt->rowCount() > 0)
				{
					$subject = "Email Reset";
					$body = "<html> <body> <h1> Hi $username! </h1>
							<br/> <h2> This email is just to confirm that your email address has been successfully changed to $newem. </h2> </body> </html>";
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					mail($newem, $subject, $body, $headers);
					echo "<script> alert('Your email address has been sucessfully changed.'); location.href='../frontend/account.php'; </script>";
				}
				else
					echo "<script> alert('Error in changing email. Please try again.'); location.href='../frontend/account.php'; </script>"; 
			}
			
		}
		else
			echo "<script> alert('Incorrect email.'); location.href='../frontend/account.php'; </script>";
	}
	else
		echo "<script> alert('Emails do not match.'); location.href='../frontend/account.php'; </script>";
}

//Change Username
if ($_POST['submit'] == "Change")
{
	$newun = $_POST['username'];
	$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username");
    $stmt->execute(['username' => $username]);

	if ($stmt->rowCount())
	{
		$statement = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username");
		$statement->execute(['username' => $newun]);
		
		if ($statement->rowCount())
			echo "<script> alert('Username not available - please choose another.'); location.href='../frontend/account.php'; </script>";
		else
		{
			//Update user table
			$stmt = $con->prepare("UPDATE `users` SET `username` = :newun WHERE `username` = :username");
			$stmt->execute(["newun" => $newun, "username" => $username]);
			//Update all images posted by user
			$stmt = $con->prepare("UPDATE `images` SET `username` = :newun WHERE `username` = :username");
			$stmt->execute(["newun" => $newun, "username" => $username]);
			//Update all comments by user
			$stmt = $con->prepare("UPDATE `comments` SET `username` = :newun WHERE `username` = :username");
			$stmt->execute(["newun" => $newun, "username" => $username]);
			//Update all likes from user
			$stmt = $con->prepare("UPDATE `likes` SET `username` = :newun WHERE `username` = :username");
			$stmt->execute(["newun" => $newun, "username" => $username]);
			$_SESSION['id'] = $newun;
			echo "<script> alert('Your username has been successfully changed!'); location.href='../frontend/account.php'; </script>";
		}
	}
	else
		echo "<script> alert('Error in changing username. Please try again.'); location.href='../frontend/account.php'; </script>";
}

//Delete Account
if ($_POST['submit'] === "Delete")
{
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = serialize(hash("whirlpool", $_POST['passwd']));
	$confirm = serialize(hash("whirlpool", $_POST['confirm']));
	if ($username == $_SESSION['id'])
	{
		if ($password == $confirm)
		{
			$subject = "Camagru Account Deleted";
			$message = "<html> <body> <h1> Your Camagru account for user $username has been deleted. </h1>
						<h2> We're sorry to see you go! </h2> </body> </html>";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			mail($email, $subject, $message, $headers);

			//Delete user account
			$stmt = $con->prepare("DELETE FROM `users` WHERE `username` = :username AND `passwd` = :password AND `email` = :email");
			$stmt->execute(['username' => $username, 'password' => $password, 'email' => $email]);
			//Delete all user uploaded photos
			$stmt = $con->prepare("DELETE FROM `images` WHERE `username` = :username");
			$stmt->execute(["username" => $username]);
			//Delete all user posted comments
			$stmt = $con->prepare("DELETE FROM `comments` WHERE `username` = :username");
			$stmt ->execute(["username" => $username]);
			//Delete all user likes
			$stmt = $con->prepare("DELETE FROM `likes` WHERE `username` = :username");
			$stmt->execute(["username" => $username]);
			
			$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username AND `passwd` = :password AND `email` = :email");
    		$stmt->execute(['username' => $username, 'password' => $password, 'email' => $email]);
			
			if ($stmt->rowCount())
				echo "<script> alert('Error in account deletion. Please try again.'); location.href='../index.php'; </script>";
			else {
				session_destroy();
				echo "<script> alert(`We're sorry to see you go. Your account has been deleted.`); location.href='../index.php'; </script>";
			}
		}
		else
			echo "<script> alert('Passwords do not match.'); location.href='../frontend/account.php'; </script>";
	}
	else
		echo "<script> alert('Invaid username.'); location.href='../frontend/account.php'; </script>";
}
else
	echo "<script> alert('Error in account deletion. Please try again.'); location.href='../frontend/account.php'; </script>";

$con = NULL;

?>