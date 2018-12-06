<?php

session_start();

include "../config/connect.php";

$user = $_SESSION["id"];
$image = base64_decode($_GET["img"]);

$stmt = $con->prepare("SELECT * FROM `comments` INNER JOIN images ON images.image_id = comments.image_id WHERE images.image = :image");
$stmt->execute(['image' => $image]);

?>
<html>
	<head>
		<title> Comments </title>
		<link rel = "stylesheet" href = "../extras/style.css">
	</head>
	<body bgcolor = "#FFFFFF">
		<div class = mainpage>
			
			<h1> Camagru </h1>
			<hr color = "#4ABDAC"/>
			<a href = "../backend/logout.php"> <img src = "https://d30y9cdsu7xlg0.cloudfront.net/png/175197-200.png" title = "Log Out" align = right style = "height: 20px; opacity: 0.75%; margin-top: 16px;"/> </a>
			
			<h2> Comments > <?php echo $user; ?> </h2>
		
			<div class = menu>
				<div class = dropdown> <a href = "./gallery.php" style = "color: #4ABDAC;"> Gallery </a> </div>
				<div class = dropdown> <a href = ""> Create </a> 
					<div class = dropdown-content>
						<h4> <a href = "./upload.php"> Upload Image </a> </h4>
						<h4> <a href = "./webcam.php"> Capture Image </a> </h4>
					</div>
				</div>
				<a href = "./account.php"> Account </a>
			</div>
			
			<br/>
			<hr color = "#4ABDAC"/>
			<br/>
			<div class = "row">
				<?php
					echo "<div class = 'column-2'>
						 <img src ='" . $image . "' width = '100%'/>
						 </div>
						 <div class = 'column-2'>
						 	<div class = 'comments'>";
					while ($comments = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$user = $comments["username"];
						$comm = $comments["comment"];
						echo "<h7 style = 'font-size: 15px;'> $user: <br/> $comm </h7>
							 <br/>
							 <br/>";
					}
					echo "</div>
						</div>";
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
</html>