var overlayButton;

function	sendOverlay(overlay) {
    overlayButton = overlay;
    xhttp = new XMLHttpRequest;
    xhttp.open("POST", "../backend/img_upload.php", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send("overlay=" + overlay);
    xhttp.onload = function() {
        if (xhttp.status >= 200 && xhttp.status < 400) {
        }
    };
}

var upload = document.getElementById("upload");

upload.addEventListener("click", function() {
    if (overlayButton == null)
        alert("Please first select an image overlay.");
});

var saveButton = document.getElementById("save");

saveButton.addEventListener("click", function() {
	image = document.getElementById("img");
	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "../backend/save_image.php", true);
	xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhttp.send("save=" + image.src);
	xhttp.onload = function() {
		if (xhttp.status >= 200 && xhttp.status < 400) {
			if (xhttp.responseText.trim() == "true") {
				alert("Image successfully posted!");
				location.href = "../frontend/webcam.php";
			} else {
	    		alert("Error in posting image. Please try again.");
			}
		};
	}
});
