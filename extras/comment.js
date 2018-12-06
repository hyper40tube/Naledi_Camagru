function imageComment(i) {
	var user = document.getElementById("user").value,
	image = document.getElementById("image" + i).value;

	if (user == null || !user) {
		alert("Please log in or create an account to comment on an image.");
		location.href = "../index.php";
	} else {
        var comment = prompt("What comment would you like to add to the table?");
		xhttp = new XMLHttpRequest();
		xhttp.open("POST", "../backend/comment.php", true);
		xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhttp.send("img=" + image + "&user=" + user + "&comment=" + comment);
		xhttp.onload = function() {
			if (xhttp.status >= 200 && xhttp.status < 400) {
			}
		};
	}
}