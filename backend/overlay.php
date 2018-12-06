<?php

session_start();

if (isset($_POST["img"]) && isset($_POST["overlay"])) {
	$temp = $_POST["img"];
	$overlay = $_POST["overlay"];

	$temp = str_replace("data:image/png;base64,", "", $temp);
	$temp = str_replace(" ", "+", $temp);
	$data = base64_decode($temp);
	$file = "../temp/" . uniqid() . ".png";
	file_put_contents($file, $data);

	$img = imagecreatefrompng($file);
	$overlayimg = imagecreatefrompng("../extras/overlays/" . $overlay . ".png");
	list($w, $h) = getimagesize("../extras/overlays/" . $overlay . ".png");
	list($nw, $nh) = getimagesize($file);
	if ($overlay === "eaglenebula" || $overlay === "trifidnebula" || $overlay === "horsehead" || $overlay === "jewelbox") {
		$overlayimg = imagescale($overlayimg, $nw, $nh);
		imagecopymerge($img, $overlayimg, 0, 0, 0, 0, $nw, $nh, 50);
	} else {
		$nw /= 1.65;
		$nh /= 1.2;
		imagecopyresized($img, $overlayimg, 130, 0, 0, 0, $nw, $nh, $w, $h);
	}
	$img_file = uniqid() . ".png";
	$output = "../images/" . $img_file;
	$_SESSION["filename"] = $img_file;
	imagepng($img, $output);
	echo "<img src = '$output' id = 'edit' width = '100%'/>";

	imagedestroy($img);
	imagedestroy($overlayimg);
	unlink($file);
}

?>