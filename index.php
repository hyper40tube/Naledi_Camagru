<?PHP

session_start();

include "config/connect.php";

$url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = trim(htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

$_SESSION['url'] = $escaped_url;

$token = $_GET['token'];
$email = $_GET['email'];

$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `token` = :token AND `email` = :email");
$stmt->execute(['token' => $token, 'email' => $email]);

if ($stmt->rowCount())
{
	$sql = "UPDATE `users` SET `verified` = '1' WHERE `token` = '$token' AND `email` = '$email'";
	$result = $con->query($sql);
	
	if ($result)
		echo "<script> alert('User successfully verified!'); location.href='frontend/account.php'; </script>";
	else
		echo "<script> alert('Error in user verification. Please try again.'); location.href='index.php'; </script>";
}

?>
<!DOCTYPE html>
<html> 
	<head>
		<title> Camagru </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "extras/style.css">
	</head> 
	<body bgcolor = "#FFFFFF">
        <div class = mainpage>
            
            <h1> Camagru </h1>
            <a href = "./backend/logout.php"> <img src = "https://d30y9cdsu7xlg0.cloudfront.net/png/175197-200.png" title = "Log Out" align = right style = "height: 20px; opacity: 0.75%; margin-top: 16px;"/> </a>
            <hr color = "#4ABDAC"/>
            
            <h2> Login / Register</h2>
            <div class = menu>
                <div class = dropdown> <a href = "./frontend/gallery.php"> Gallery </a> </div>
                <div class = dropdown> <a href = ""> Create </a>
					<div class = dropdown-content>
                        <h4> <a href = "./frontend/upload.php"> Upload Image </a> </h4>
                        <h4> <a href = "./frontend/webcam.php"> Capture Image </a> </h4>
                    </div>
                </div>
                <a href = "./frontend/account.php"> Account </a>
            </div>
            <hr color = "#4ABDAC"/>
            
        <div class = landing>
            <h2> Already have an account? Login below: </h2>
            <form action="backend/index_credentials.php" method="POST" title="login">
			 Username: <input type="text" name="username" required="required" style="margin-left: 18px;"/>
			 <br/>
			 Password: <input type="password" name="passwd" required="required" style="margin-left: 26px;"/>
             <br/>
             <br/>
            <input type="submit" name="submit" value="Login"/>
            </form>
            <hr color = "#FC4A1A"/>
            <h2> Need an account? Sign up below: </h2>
            <form action="backend/index_credentials.php" method="POST" title="register">
             Name: <input type="text" name="name" required="required" style="margin-left: 137px;"/>
             <br/>
             Surname: <input type="text" name="surname" required="required" style="margin-left: 103px;"/>
             <br/>
			 Username: <input type="text" name="username" required="required" style="margin-left: 92px;"/>
			 <br/>
             Email: <input type="email" name="email" placeholder="Enter your email" required="required" style="margin-left: 140px;"/>
             <br/>
			 Password: <input type="password" name="passwd" placeholder="Alphanumeric, At Least 8 Characters Long" pattern="(?=.*[0-9])(?=.*[A-Z])([a-z A-Z 0-9]+)(\w{7,})" required="required" title="Password must be alphanumeric with at least 8 characters and at least one uppercase character" style="margin-left: 100px;"/>
             <br/>
             Confirm Password: <input type="password" name="confirm" required="required" style = "margin-left: 10px;"/>
             <br/>
             <br/>
			 <input type="submit" name="submit" value="Register"/>
            </form>
			<hr color = "#FC4A1A"/>
			<h2> Forgot Password? </h2>
			<form action="backend/index_credentials.php" method="POST" title="reset">
			 Username: <input type="text" name="username" required="required" style="margin-left: 18px;"/>
			 <br/>
			 Email: <input type="email" name="email" title="Enter your email" required="required" style="margin-left: 66px;"/>
             <br/>
             <br/>
            <input type="submit" name="submit" value="Reset"/>
            </form>
        </div>
            <hr color = "#4ABDAC"/>
            <br/>
            <footer> nmatutoa | 2017 </footer>
            <br/>
            <br/>
        </div>
    </body>
</html>
