var video = document.querySelector("#video");

if (navigator.mediaDevices.getUserMedia) {
	navigator.mediaDevices.getUserMedia({ video: true })
		.then(function (stream) {
			video.srcObject = stream;
		})
		.catch(function (error) {
		});
}

var canvas = document.getElementById("canvas"),
video = document.getElementById("video"),
button = document.getElementById("capture"),
overlayButton = null;

function	checkOverlay(overlay) {
	if (overlay) {
		overlayButton = overlay;
		var mock = document.getElementById("mock"),
		opacity = "1",
		width = "60%",
		margin = "20.5%";
		height = null;
		if (overlay == "eaglenebula" || overlay == "trifidnebula" || overlay == "horsehead" || overlay == "jewelbox") {
			opacity = "0.4";
			width = "100%";
			height = "height: 18.8vw;";
			margin = "0";
		}
		mock.innerHTML = "<img src = '../extras/overlays/" + overlay + ".png' width = '" + width + "' style = 'z-index: 2; margin-left: " + margin + "; opacity: " + opacity + ";" + height + "'/>";
	}
}

function imageCapture() {
	canvas.width = video.videoWidth;
	canvas.height = video.videoHeight;
	canvas.getContext("2d").drawImage(video, 0, 0);
	button.innerText = "Retake";
	var image = canvas.toDataURL("image/png");

	xhttp = new XMLHttpRequest();
	xhttp.open("POST", "../backend/overlay.php", true);
	xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhttp.onload = function() {
		if (xhttp.status >= 200 && xhttp.status < 400) {
			var overlay = document.getElementById("overlay");
			overlay.style.display = "block";
			overlay.innerHTML = xhttp.responseText;
		}
	};
	xhttp.send("img=" + image + "&overlay=" + overlayButton);
};

button.addEventListener("click", function() {
	if (button.innerText === "Capture") {
		if (overlayButton === null)
			alert("Please select an overlay image.")
		else
			imageCapture();
	}
	else {
		button.innerText = "Capture";
		document.getElementById("overlay").style.display= "none";
	}
});

var saveButton = document.getElementById("save");

saveButton.addEventListener("click", function() {
	image = document.getElementById("edit"),
	overlay = document.getElementById("overlay");
	if (button.innerText == "Retake") {
		xhttp = new XMLHttpRequest();
		xhttp.open("POST", "../backend/save_image.php", true);
		xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhttp.send("save=" + image.src);
		xhttp.onload = function() {
			if (xhttp.status >= 200 && xhttp.status < 400) {
				if (xhttp.responseText.trim() == "true") {
					alert("Image successfully posted!");
					location.reload();
				} else {
					alert("Error in posting image. Please try again.");
				}
			};
		}
	}
});