<?PHP

session_start();

include "../config/connect.php";

if (isset($_SESSION['id']))
	$user = $_SESSION['id'];

$stmt = $con->prepare("SELECT `username` FROM `users` WHERE `username` = :username AND `verified` = :verified");
$stmt->execute(['username' => $user, 'verified' => 1]);
if ($stmt->rowCount() < 1)
	echo "<script> alert('Please login or register to create an image.'); location.href='../index.php'; </script>";

$stmt = $con->prepare("SELECT * FROM `images` WHERE `username` = :username");
$stmt->execute(['username' => $user]);

?>
<html>
	<head>
		<title> Capture Image </title>
		<link rel = "stylesheet" href = "../extras/style.css">
	</head>
	<body bgcolor = "#FFFFFF">
		<div class = mainpage>

			<h1> Camagru </h1>
			<hr color = "#4ABDAC"/>
			<a href = "../backend/logout.php"> <img src = "https://d30y9cdsu7xlg0.cloudfront.net/png/175197-200.png" title = "Log Out" align = right style = "height: 20px; margin-top: 16px;"/> </a>

			<h2> Capture </h2>

			<div class = menu>
				<div class = "dropdown"> <a href = "./gallery.php" > Gallery </a> </div>
				<div class = dropdown> <a href = "#" style = "color: #4ABDAC;"> Create </a>
					<div class = dropdown-content>
						<h4> <a href = "./upload.php"> Upload Image </a> </h4>
						<h4> <a href = "#"> Capture Image </a> </h4>
					</div>
				</div>
				<a href = "./account.php"> Account </a>
			</div>

			<br/>
			<hr color = "#4ABDAC"/>
			<br/>

			<div class = center-panel>
				<div class = left-panel>
					<p style = "color: #606061; font-size: 1.1vw;"> Select an overlay below to add to your image before capturing: </p>
					<br/>
					<div class = "overlay-select-1">
						<input type = "image" src = "../extras/overlays/wonderwoman.png" width = "20%" onclick = "checkOverlay('wonderwoman')"/>
						<br/>
						<input type = "image" src = "../extras/overlays/theflash.png" width = "20%" onclick = "checkOverlay('theflash')"/>
						<br/>
						<input type = "image" src = "../extras/overlays/vader.png" width = "20%" onclick = "checkOverlay('vader')"/>
						<br/>
						<input type = "image" src = "../extras/overlays/ironman.png" width = "20%" onclick = "checkOverlay('ironman')"/>
						<br/>
					</div>
					
					<div class = "mock-overlay" id = "mock"> </div>
					<video id = 'video' autoplay> </video>
					<button id = "capture" style = "margin-top: 20vw;"> Capture </button>
					<button id = "save" style = "margin-top: 20vw;"> Save and Post! </button>
					<div class = "overlay-select-2">
						<input type = "image" src = "../extras/overlays/eaglenebula.png" width = "30%" onclick = "checkOverlay('eaglenebula')"/>
						<br/>
						<input type = "image" src = "../extras/overlays/trifidnebula.png" width = "30%" onclick = "checkOverlay('trifidnebula')"/>
						<br/>
						<input type = "image" src = "../extras/overlays/horsehead.png" width = "30%" onclick = "checkOverlay('horsehead')"/>
						<br/>
						<input type = "image" src = "../extras/overlays/jewelbox.png" width = "30%" onclick = "checkOverlay('jewelbox')"/>
						<br/>
					</div>
				</div>
				<div class = right-panel>
				<div class = "overlay" id = "overlay"></div>
					<canvas id = 'canvas'></canvas>
				</div>
			</div>
			<hr color = "#4ABDAC"/>
			<div class = "row">
			<p style = "color: #606061; font-size: 1.1vw;"> Previous Posts </p>
			<?php
					if ($stmt->rowCount() < 1)
						echo "<h2 style = 'font-size: 1vw;'> You haven't posted any images :( Head over to the 'Create' tab to change that </h2>";
					else {
						while ($images = $stmt->fetch(PDO::FETCH_ASSOC)) {
							$image = $images["image"];
							$likes = $images["likes"];
							$comm = $images["comments"];
							echo "<div class = 'column-5'> 
									<img src ='" . $image . "' width = '75%'/>
									<br/>
									<ul class = 'profile'>
									<li> <a href = './likes.php?img=" . base64_encode($image) . "'><h5> $likes Like(s) </h5> </a> </li>
									<li> <a href = './comments.php?img=" . base64_encode($image) . "'><h5> $comm Comment(s) </h5> </a> </li>
									<li> <a href = '../backend/delete.php?img=" . base64_encode($image) . "'><h5> Delete </h5> </a> </li>
									</ul>
								 </div>";
						}
					}
				?>
			</div>
		<br/>
		<hr color = "#4ABDAC"/>
			<br/>
			<footer> nmatutoa | 2018 </footer>
		<br/>
		<br/>
		</div>
	</body>
	<script src = "../extras/capture.js"></script>
</html>