<?php

session_start();

include "../config/connect.php";

if (isset($_SESSION["img"]))
    unset($_SESSION["img"]);

if (isset($_POST["save"])) {
    $user = $_SESSION["id"];
    $image = $_POST["save"];

    $stmt = $con->prepare("INSERT INTO `images` (username, image) VALUES (:user, :image)");
    $stmt->execute(["user" => $user, "image" => $image]);

    if ($stmt->rowCount())
        echo "true";
    else
        echo "false";
}
    
$con = NULL;

?>

