<?php

include "../config/connect.php";

//Get image, username and comment of person who wants to comment on post
$image = $_POST["img"];
$user = $_POST["user"];
$comment = $_POST["comment"];

//Get image_id to use for 'comments' table
$stmt = $con->prepare("SELECT `image_id` FROM `images` WHERE `image` = :image");
$stmt->execute(["image" => $image]);

$id = $stmt->fetch(PDO::FETCH_ASSOC);
$id = $id["image_id"];


//Update 'images' and 'comments' table - add new comment and tally 'comments' in images table
$stmt = $con->prepare("UPDATE `images` SET `comments` = `comments` + 1 WHERE `image_id` = '$id'");
$stmt->execute();

$stmt = $con->prepare("INSERT INTO `comments` (`username`, `image_id`, `comment`) VALUES (:user, :id, :comment)");
$stmt->execute(["user" => $user, "id" => $id, "comment" => $comment]);

if ($stmt->rowCount() > 0) {
	//Obtain username of person who posted commented image. Needed to get email address
	$stmt = $con->prepare("SELECT images.`username` FROM `images` INNER JOIN comments ON comments.image_id = '$id' AND images.image_id = '$id'");
	$stmt->execute();
	$username = $stmt->fetch(PDO::FETCH_ASSOC);
	$username = $username["username"];

	//Check if user has notifications enabled
	$stmt = $con->prepare("SELECT `notifications` FROM `users` WHERE `username` = :username");
	$stmt->execute(["username" => $username]);
	$noti = $stmt->fetch(PDO::FETCH_ASSOC);
	$noti = $noti["notifications"];

	if ($noti == 1) {
		//Send email if notifications enabled
		$stmt = $con->prepare("SELECT users.`email` FROM `users` INNER JOIN images ON images.username = '$username' AND users.username = '$username'");
		$stmt->execute();
		$email = $stmt->fetch(PDO::FETCH_ASSOC);
		$email = $email["email"];
		$message = "<html> <body> <h1> Hi $username! </h1>
				<br/> <h2> $user said: '$comment' </h2>
				<br/> <h3> Head on over to your profile to see who else is talking about your post!";
		$subject = "Someone commented on your post!";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		mail($email, $subject, $message, $headers);
	}
	echo "Success";
} else
	echo "Failure";

$con = NULL;

?>