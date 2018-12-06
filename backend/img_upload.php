<?php

session_start();

$directory = "../temp/";
$file = $directory . uniqid() . ".png";
$image_type = strtolower(pathinfo($file, PATHINFO_EXTENSION));

if (isset($_POST["overlay"]))
	$_SESSION["overlay"] = $_POST["overlay"];

//Check if file is an image
if ($_POST['submit'] == "Upload")
{
	$check = getimagesize($_FILES['file_upload']['tmp_name']);
	if ($check != false)
	{
        //Check that file name doesn't already exist
        if (!file_exists($file))
        {
            //Check that image type is either JPEG, JPG or PNG
            if ($image_type == 'jpg' || $image_type == 'jpeg' || $image_type == 'png')
            {
                //Check that image size does not exceed 5MB
				if ($_FILES['file_upload']['size'] <= 5000000)
				{
					//After all checks are confirmed - assign a value of 1 to upload_status. This means that the file checks out and is ready for upload.
					$upload_status = 1;
				}
                else
                {
					$upload_status = 0;
                    echo "<script> alert('Image size too large. Maximum upload size is 5MB. Please choose another file.'); </script>";
                }
            }
            else
            {
				$upload_status = 0;
                echo "<script> alert('File type invalid. Please choose either a jpg, jpeg or png type image.'); </script>";
            }
        }
        else
        {
			$upload_status = 0;
            echo "<script> alert('File name already exists. Please choose another.'); </script>";
        }
	}
	else
	{
		$upload_status = 0;
		echo "<script> alert('File type invalid - please select either a jpg, jpeg or png image.'); </script>";
	}
	//Final file upload to database and saving locally
	if ($upload_status == 1)
    {
		if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $file))
		{
			$overlay = $_SESSION["overlay"];
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
			imagepng($img, $output);
			$_SESSION["img"] = $output;
			imagedestroy($img);
			imagedestroy($overlayimg);
			unlink($file);
			echo "<script> alert('Image successfully uploaded!'); location.href='../frontend/upload.php'; </script>";
		}
		else
			echo "<script> alert('Error in image upload - please try again.'); location.href='../frontend/upload.php'; </script>";
	}
	else
		echo "<script> alert('Error in image upload - please try again.'); location.href='../frontend/upload.php'; </script>";
}

$con = NULL;

?>