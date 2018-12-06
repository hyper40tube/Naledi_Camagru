<?PHP

include "database.php";

try {
	$con = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "USE camagru";
	$con->exec($sql);
	//echo "Connection Successful" . "<br/>";
}

catch (PDOException $e) {
	echo $e->getMessage();
}

?>
