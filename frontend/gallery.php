<?php 

session_start();

include "../config/connect.php";

if (isset($_SESSION["id"]))
	$user = $_SESSION["id"];

if (isset($_GET["pageno"]))
	$pageno = $_GET["pageno"];
else
	$pageno = 1;

$num = 9;
$offset = ($pageno - 1) * $num;

$stmt = $con->prepare("SELECT * FROM `images` ORDER BY `date_posted` DESC");
$stmt->execute();

?>
<html>
	<head>
		<title> Gallery </title>
		<link rel = "stylesheet" href = "../extras/style.css">
	</head>
	<body bgcolor = "#FFFFFF">
		<div class = mainpage>
			
			<h1> Camagru </h1>
			<hr color = "#4ABDAC"/>
			<a href = "../backend/logout.php"> <img src = "https://d30y9cdsu7xlg0.cloudfront.net/png/175197-200.png" title = "Log Out" align = right style = "height: 20px; opacity: 0.75%; margin-top: 16px;"/> </a>
			
			<h2> Gallery </h2>
		
			<div class = menu>
				<div class = "dropdown"> <a href = "#" style = "color: #4ABDAC;"> Gallery </a> </div>
				<div class = dropdown> <a href = "#"> Create </a> 
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

				<center> <div class = "row">
				<?php
					if ($stmt->rowCount() < 1)
						echo "<h2> It seems that no one has posted anything yet... Why don't you be the first? </h2>";
					else {
						$total_rows = $stmt->rowCount();
						$total_pages = ceil($total_rows / $num);

						$stmt = $con->prepare("SELECT * FROM `images` ORDER BY `date_posted` DESC LIMIT $offset, $num");
						$stmt->execute();
						$i = 0;
						while ($images = $stmt->fetch(PDO::FETCH_ASSOC)) {
							$image = $images["image"];
							$likes = $images["likes"];
							$comm = $images["comments"];
							$poster = $images["username"];
							echo "<div class = 'column'> 
									<img src ='" . $image . "' width = '75%'/>
									<br/>
									<input type = 'hidden' id = 'user' value = '$user'>
									<input type = 'hidden' id = 'like$i' value = '$likes'>
									<input type = 'hidden' id = 'image$i' value = '$image'>
									<input type = 'hidden' id = 'comment$i' value = '$comm'>
									<h6 id = 'poster'> $poster </h6>
									<h6 id = 'likes' class = 'click' onclick='likeImage($i);'> Like ($likes Likes) </h6> </a>
									<h6 id = 'comments' class = 'click' onclick='imageComment($i);'> Add Comment ($comm Comments) </h6>
								 </div>";
							$i++;
						}
					}
				?>
				</div> </center>
			<br/>
			<ul class = "pagination">
				<li><a href = "?pageno=1"> First </a></li> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<li class = "<?php if($pageno <= 1) echo 'disabled'; ?>">
					<a href = "<?php if($pageno <= 1) echo '#'; else echo "?pageno=".($pageno - 1); ?>"> Prev </a>
				</li> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<li class = "<?php if($pageno >= $total_pages) echo 'disabled'; ?>">
					<a href = "<?php if($pageno >= $total_pages) echo '#'; else echo "?pageno=".($pageno + 1); ?>"> Next </a>
				</li> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<li><a href = "?pageno= <?php echo $total_pages; ?>"> Last </a></li>
			</ul>
			<br/>
			<hr color = "#4ABDAC"/>
			<br/>
			<footer> nmatutoa | 2018 </footer>
			<br/>
			<br/>
		</div>
	</body>
	<script src = "../extras/like_comm.js"></script> 
</html>