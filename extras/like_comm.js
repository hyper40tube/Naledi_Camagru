function likeImage(i) {
	var user = document.getElementById("user").value,
		image = document.getElementById("image" + i).value;

	if (user == null || !user) {
		alert("Please log in or create an account to like an image.");
		location.href = "../index.php";
	} else {
		xhttp = new XMLHttpRequest();
		xhttp.open("POST", "../backend/like.php", true);
		xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhttp.send("img=" + image + "&user=" + user);
		xhttp.onload = function () {
			if (xhttp.status >= 200 && xhttp.status < 400) {
				console.log(xhttp.responseText);
			}
		};
	}
}

function imageComment(i) {
	var user = document.getElementById("user").value,
		image = document.getElementById("image" + i).value;

	if (user == null || !user) {
		alert("Please log in or create an account to like an image.");
		location.href = "../index.php";
	} else {
		var comment = prompt("What comment would you like to add to the table?");
		if (comment) {
			xhttp = new XMLHttpRequest();
			xhttp.open("POST", "../backend/comment.php", true);
			xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhttp.send("img=" + image + "&user=" + user + "&comment=" + comment);
			xhttp.onload = function () {
				if (xhttp.status >= 200 && xhttp.status < 400) {
				}
			};
		}
	}
}