<?PHP

session_start();

include "../config/connect.php";

if (isset($_SESSION['id']))
	$user = $_SESSION['id'];

$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username AND `verified` = :verified");
$stmt->execute(['username' => $user, 'verified' => 1]);
if ($stmt->rowCount() < 1)
	echo "<script> alert('Please login or register to upload an image.'); location.href='../index.php'; </script>";

?>
<html>
	<head>
		<title> Upload Image </title>
		<link rel = "stylesheet" href = "../extras/style.css">
	</head>
	<body bgcolor = "#FFFFFF">
		<div class = mainpage>

			<h1> Camagru </h1>
			<hr color = "#4ABDAC"/>
			<a href = "../backend/logout.php"> <img src = "https://d30y9cdsu7xlg0.cloudfront.net/png/175197-200.png" title = "Log Out" align = right style = "height: 20px; margin-top: 16px;"/> </a>

			<h2> Upload </h2>

			<div class = menu>
				<div class = dropdown> <a href = "./gallery.php"> Gallery </a> </div>
				<div class = dropdown> <a href = "#"  style = "color: #4ABDAC;"> Create </a>
					<div class = dropdown-content>
                        <h4> <a href = "#"> Upload Image </a> </h4> 
                        <h4> <a href = "webcam.php"> Capture Image </a> </h4>
                    </div>
				</div>
				<a href = "account.php"> Account </a>
			</div>

			<br/>
			<hr color = "#4ABDAC"/>
			<br/>

			<div class = center-panel>
				<div class = left-panel>
					<h3> Select Image for Upload </h3>
					<form action = '../backend/img_upload.php' method = 'post' enctype='multipart/form-data'>
						<input type = 'file' name = 'file_upload' required = required>
						<input type = 'submit' value = 'Upload' name = 'submit' id = "upload">
					</form>
					<div class = overlay-select>
						<div class = images>
							<input type = "image" src = "../extras/overlays/wonderwoman.png" width = "100vw" onclick = "sendOverlay('wonderwoman')"/>
							<input type = "image" src = "../extras/overlays/theflash.png" width = "100vw" onclick = "sendOverlay('theflash')"/>
							<input type = "image" src = "../extras/overlays/vader.png" width = "100vw" onclick = "sendOverlay('vader')"/>
							<input type = "image" src = "../extras/overlays/ironman.png" width = "100vw" onclick = "sendOverlay('ironman')"/>
							<br/>
							<br/>
							<input type = "image" src = "../extras/overlays/eaglenebula.png" width = "100vw" onclick = "sendOverlay('eaglenebula')"/>
							<input type = "image" src = "../extras/overlays/trifidnebula.png" width = "100vw" onclick = "sendOverlay('trifidnebula')"/>
							<input type = "image" src = "../extras/overlays/horsehead.png" width = "100vw" onclick = "sendOverlay('horsehead')"/>
							<input type = "image" src = "../extras/overlays/jewelbox.png" width = "100vw" onclick = "sendOverlay('jewelbox')"/>
						</div>
					</div>
				</div>

				<div class = right-panel>
					<div class = "capture">
						<?php if (isset($_SESSION["img"])) echo "<img src = '" . $_SESSION["img"] . "' id = 'img' width = 100%/>"; ?>
						<br/>
						<button id = 'save' style = "width: 35vw; margin-top: 1vw;"> Save and Post! </button>
					</div>
				</div>
			</div>
		<br/>
		<hr color = "#4ABDAC"/>
			<br/>
			<footer> nmatutoa | 2017 </footer>
		<br/>
		<br/>
		</div>
	</body>
	<script src = "../extras/upload.js"> </script>
</html>