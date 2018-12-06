<?PHP

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../config/connect.php";

//Login 
if ($_POST['submit'] == "Login")
{	
	if ($_POST['username'] != $_SESSION['id'] && $_SESSION['id'] != NULL)
	{
		$user = $_SESSION['id'];
		echo "<script> alert('User $user is currently logged in. Please log out before trying to log in with a different account.'); location.href='../frontend/account.php'; </script>";
	}
	else
	{
		$username = $_POST['username'];
		$passwd = serialize(hash("whirlpool", $_POST['passwd']));
		
		$stmt = $con->prepare("SELECT * FROM `users` WHERE `username` = :username");
		$stmt->execute(['username' => $username]);

		if ($stmt->rowCount())
		{
			$stmt = $con->prepare("SELECT * FROM `users` WHERE `verified` = :verified AND `username` = :username");
			$stmt->execute(['verified' => 1, 'username' => $username]);
			if ($stmt->rowCount())
			{
				$stmt = $con->prepare("SELECT * FROM `users` WHERE `passwd` = :passwd");
				$stmt->execute(['passwd' => $passwd]);
				if ($stmt->rowCount())
				{
					if ($_SESSION['id'] == $username)
						echo "<script> alert('You are already logged in as $username.'); location.href='../frontend/account.php'; </script>";
					else
					{
						$_SESSION['id'] = $username;
						header("Location: ../frontend/account.php");
					}
				}
				else
					echo "<script> alert('Invalid password. Please try again.'); location.href='../index.php'; </script>";
			}
			else
				echo "<script> alert('User not verified. Please refer to your inbox for further instructions.'); location.href='../index.php'; </script>";
		}	
		else
			echo "<script> alert('Invalid username. Please try again.'); location.href='../index.php'; </script>";
	}
}

//Register

if ($_POST['submit'] == "Register")
{
	$url = $_SESSION['url'];
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$passwd = $_POST['passwd'];
	$confirm = $_POST['confirm'];
	$token = mt_rand();
		
	if ($passwd == $confirm)
	{
		$passwd = serialize(hash("whirlpool", $_POST['passwd']));

    	$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username");
    	$stmt->execute(['username' => $username]);

		if ($stmt->rowCount() == 0)
		{
			$stmt = $con->prepare("SELECT `email` FROM `users` WHERE `email` = :email");
    		$stmt->execute(['email' => $email]);

			if ($stmt->rowCount() == 0)
			{
				$stmt = $con->prepare("INSERT INTO `users` (name, surname, username, email, passwd, token) VALUES (:name, :surname, :username, :email, :passwd, :token)");
				$stmt->execute(["name" => $name, "surname" => $surname, "username" => $username, "email" => $email, "passwd" => $passwd, "token" => $token]);

				$url = "http:" . $url . "?token=" . $token . "&email=" . $email;
                $subject = "Thank you for registering your Camagru account!";
				$body =	"<html> <body> <h1> Hello $name and welcome to Camagru! </h1>
						<h2> Please click <a href = $url>here</a> to verify your Camagru account. </h2> </body> </html>";
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";	
				mail($email, $subject, $body, $headers);

				$_SESSION['email'] = $email;
				$_SESSION['id'] = $username;
				$_SESSION['token'] = $token;
				$_SESSION['passwd'] = $passwd;
				$_SESSION['verified'] = 1;
				
				header("Location: ../verify.html");
			}
			else
				echo "<script> alert('Email Address already in use. Please try another one or if this email belongs to you - try logging in.'); location.href='../index.php'; </script>";
		}
		else
			echo "<script> alert('Username already exists. Please try another one.'); location.href='../index.php'; </script>";
	}
	else
	{
		echo "<script> alert('Passwords do not match'); location.href='../index.php'; </script>";
	}
}

//Forgot Password

if ($_POST['submit'] == 'Reset')
{
	$username = $_POST['username'];
	$email = $_POST['email'];
	$temp_pw = (md5(mt_rand()));
		
	$body = "<html> <body> <h1> Hi " . $username . ".</h1> 
			<br/> <h2> It appears that you have forgotten your password.
			Please use this temporary password to login and then immediately change your password in the 'Account' tab on the website.
			Your temporary password is " . $temp_pw . ". Copy and paste it into the 'Old Password' field. </h2> </body> </html>";
	$subject = "Oh no! You forgot your password?";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";		
	$_SESSION['id'] = $username;
		
	$stmt = $con->prepare("SELECT * FROM `users` WHERE `username` = :username AND `email` = :email");
	$stmt->execute(['username' => $username, 'email' => $email]);
	if ($stmt->rowCount() > 0)
	{
		$temp_pw = serialize(hash("whirlpool", $temp_pw));
		$stmt = $con->prepare("UPDATE `users` SET `passwd` = :temp_pw WHERE `username` = :username");
		$stmt->excute(["temp_pw" => $temp_pw, "username" => $username]);
		if ($stmt->rowCount() > 0) {
			mail($email, $subject, $body, $headers);
			echo "<script> alert('An email with details for your password reset has been sent to $email. Please check your inbox.'); location.href='../frontend/account.php'; </script>";
		}
	}
	else
		echo "<script> alert('Username and email combination does not match'); location.href='../index.php'; </script>";
}

$con = NULL;

?>
