<?php

session_start();

include "../config/connect.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$img = base64_decode($_GET["img"]);
$username = $_SESSION["id"];

$stmt = $con->prepare("SELECT `image_id` FROM `images` WHERE `image` = :image");
$stmt->execute(["image" => $img]);
$img_id = $stmt->fetch(PDO::FETCH_ASSOC);
$img_id = $img_id["image_id"];

$stmt = $con->prepare("DELETE FROM `comments` WHERE `image_id` = :image_id");
$stmt->execute(["image_id" => $img_id]);

$stmt = $con->prepare("DELETE FROM `likes` WHERE `image_id` = :image_id");
$stmt->execute(["image_id" => $img_id]);

$stmt = $con->prepare("DELETE FROM `images` WHERE `username` = :username AND `image` = :image");
$stmt->execute(["username" => $username, "image" => $img]);

echo "<script> alert('Your image has been deleted'); location.href='../frontend/webcam.php'; </script>";

$con = NULL;

?>