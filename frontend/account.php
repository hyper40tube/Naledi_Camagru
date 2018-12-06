<?PHP

session_start();

include "../config/connect.php";

if (isset($_SESSION['id']))
	$user = $_SESSION['id'];
else
	echo "<script> alert('Please login or register to access account details.'); location.href='../index.php'; </script>";

if (isset($_SESSION['email']))
	$email = $_SESSION['email'];

$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username AND `verified` = :verified");
$stmt->execute(['username' => $user, 'verified' => 1]);
if ($stmt->rowCount())
	echo "<script> location.href='#'; </script>";
else
	echo "<script> alert('Please login or register to access account details.'); location.href='../index.php'; </script>";

$stmt = $con->prepare("SELECT `notifications` FROM `users` WHERE `username` = :username");
$stmt->execute(["username" => $user]);
$notifications = $stmt->fetch(PDO::FETCH_ASSOC);
$notifications = $notifications["notifications"];

?>

<html>
    <head>
        <title> Account </title>
        <link rel = "stylesheet" href = "../extras/style.css">
    </head>
    <body bgcolor = "#FFFFFF">
        <div class = mainpage>
            
            <h1> Camagru </h1>
            <hr color = "#4ABDAC"/>
            <a href = "../backend/logout.php"> <img src = "https://d30y9cdsu7xlg0.cloudfront.net/png/175197-200.png" title = "Log Out" align = right style = "height: 20px; opacity: 0.75%; margin-top: 16px;"/> </a>
            
            <h2> Account - <?PHP echo $user; ?> </h2>
			
			<div class = menu>
                <div class = dropdown> <a href = "./gallery.php"> Gallery </a> </div>
                <div class = dropdown> <a href = "#"> Create </a>
					<div class = dropdown-content>
                        <h4> <a href = "./upload.php"> Upload Image </a> </h4>
                        <h4> <a href = "./webcam.php"> Capture Image </a> </h4>
                    </div>
                </div>
                <a href = "account.php" style = "color: #4ABDAC;"> Account </a>
            </div>
            
            <br/>
            <hr color = "#4ABDAC"/>
            
            <div class = accounts>
                <h3> Notifications </h3>
                <form action="../backend/change_credentials.php" method="POST" title="notificaitons">
                    <h3> Would you like to receive notificaitons about likes and comments on your photos? </h3>
                    <input type="radio" name="notifications" value="Yes" <?php if ($notifications == 1) echo "checked='checked'"; ?>> Yes<br/>
                    <input type="radio" name="notifications" value="No"  <?php if ($notifications == 0) echo "checked='checked'"; ?>> No
                    <input type="submit" name="submit" value="Set Notification Settings" style = "margin-left: 5px;"/>
                </form>
                <br/>
                <hr color = "#FC4A1A"/>
                <h3> Reset Password </h3>
                <form action="../backend/change_credentials.php" method="POST" title="change_pw">
                    Old Password: <input type="password" name="oldpw" style ="margin-left: 102px;" required="required"/>
                    <br/>
                    New Password: <input type="password" name="newpw" style ="margin-left: 94px;" required="required"/>
					<br/>
					Confirm New Password: <input type="password" name="confirm" style ="margin-left: 20px;" required="required"/>
                    <input type="submit" name="submit" value="Reset" style = "margin-left: 5px;"/>
                </form>
                <br/>
                <hr color = "#FC4A1A"/>
                <h3> Update Email </h3>
                <form action="../backend/change_credentials.php" method="POST">
                    Old Email Address: <input type="email" name="oldem" style = "margin-left: 102px;" required="required"/>
                    <br/>
                    New Email Address: <input type="email" name="newem" style = "margin-left: 94px;" required="required"/>
					<br/>
					Confirm New Email Address: <input type="email" name="confirm" style = "margin-left: 21px;" required="required"/>
                    <input type="submit" name="submit" value="Update" style = "margin-left: 5px;"/>
                </form>
				 <br/>
                <hr color = "#FC4A1A"/>
                <h3> Change Username </h3>
                <form action="../backend/change_credentials.php" method="POST">
                    New Username: <input type="text" name="username" style = "margin-left: 21px;" required="required"/>
                    <input type="submit" name="submit" value="Change" style = "margin-left: 5px;"/>
                </form>
				<br/>
                <hr color = "#FC4A1A"/>
                <h3> Delete Account </h3>
				<h3> Please make sure that you are <b>ABSOLUTELY CERTAIN</b> should you wish to delete your account. This action is irreversible.</h3>
                <form action="../backend/change_credentials.php" method="POST">
                    Username: <input type="text" name="username" style = "margin-left: 87px;" required="required"/>
                    <br/>
                    Email Address: <input type="email" name="email" style = "margin-left: 53px;" required="required"/>
					<br/>
					Password: <input type="password" name="passwd" style = "margin-left: 93px;" required="required"/>
					<br/>
					Confirm Password: <input type="password" name="confirm" style = "margin-left: 20px;" required="required"/>
                    <input type="submit" name="submit" value="Delete" style = "margin-left: 5px;"/>
                </form>
            </div>
            
            <br/>
            
            <hr color = "#4ABDAC"/>
            <br/>
            <footer> nmatutoa | 2018 </footer>
            <br/>
            <br/>
        </div>
    </body>
</html>
