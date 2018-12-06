<?PHP

session_start();


include "../config/connect.php";

if ($_POST['submit'] == 'Reset')
{
	try {
		$username = $_POST['username'];
		$email = $_POST['email'];
		$temp_pw = (md5(mt_rand()));
		
		$body = "Hi " . $username . ". It appears that you have forgotten your password. Please use this temporary password to login and then immediately change your password in the 'Account' tab on the website. Your temporary password is " . $temp_pw . ". Copy and paste it into the 'Old Password' field.";
		$subject = "Oh no! You forgot your password?";
		
		$_SESSION['id'] = $username;
		
		$stmt = $con->prepare("SELECT * FROM `users` WHERE `username` = :username AND `email` = :email");
		$stmt->execute(['username' => username, 'email' => $email]);
		if ($stmt->rowCount())
		{
			mail($email, $subject, $body);
			echo "<script> alert('An email with details for your password reset has been sent to $email. Please check your inbox.); location.href='../frontend/account.php'; </script>";
		}
		else
			echo "<script> alert('Username and email combination does not match'); location.href='../index.php'; </script>";
	}
	
	catch (PDOException $e) {
		echo $e->getMessage();
	}
}

$con = NULL;

?>