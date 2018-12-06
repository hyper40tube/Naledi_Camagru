<?php

include "../config/connect.php";

//Get image and username person who likes post
$image = $_POST["img"];
$user = $_POST["user"];

//Get image_id to use for 'likes' table
$stmt = $con->prepare("SELECT `image_id` FROM `images` WHERE `image` = :image");
$stmt->execute(["image" => $image]);

$id = $stmt->fetch(PDO::FETCH_ASSOC);
$id = $id["image_id"];

$stmt = $con->prepare("SELECT * FROM `likes` WHERE `image_id` = :image_id AND `username` = :username");
$stmt->execute(['image_id' => $id, 'username' => $user]);
if ($stmt->rowCount() > 0)
	/* "User $user has already liked post."; */ ;
else {
	//Update 'images' and 'likes' table - add new like and tally 'likes' in images table
	$stmt = $con->prepare("UPDATE `images` SET `likes` = `likes` + 1 WHERE `image_id` = :id");
	$stmt->execute(["id" => $id]);

	$stmt = $con->prepare("INSERT INTO `likes` (`username`, `image_id`) VALUES (:user, :id)");
	$stmt->execute(["user" => $user, "id" => $id]);

	if ($stmt->rowCount() > 0) {
		//Obtain username of person who's image was liked. Needed to get email address
		$stmt = $con->prepare("SELECT images.`username` FROM `images` INNER JOIN `likes` ON likes.image_id = '$id' AND images.image_id = '$id'");
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
					<br/> <h2> $user liked your post! </h2>
					<br/> <h3> Head on over to your profile to see who else is giving your post some love!";
			$subject = "Someone has liked your post!";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			mail($email, $subject, $message, $headers);
		}
		echo "Success";
	} else
		echo "Failure";
}

$con = NULL;

?>