<?PHP

include "./connect.php";

try {	
    /* Db creation */
    $sql = "CREATE DATABASE IF NOT EXISTS camagru";
    $con->exec($sql);
    
    /* Users table creation */
    $sql = "USE camagru";
    $con->exec($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS users(
            `user_id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(25) NOT NULL,
            `surname` VARCHAR(25) NOT NULL,
            `username` VARCHAR(50) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `passwd` VARCHAR(1000) NOT NULL,
            `date_joined` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `token` VARCHAR(1000) NOT NULL,
            `verified` TINYINT(1) NOT NULL DEFAULT 0,
            `notifications` TINYINT(1) NOT NULL DEFAULT 1)";
    $con->exec($sql);   
    //echo "Table 'users' created" . "<br/>";
    
    /* Images table creation */
	$sql = "USE camagru";
    $con->exec($sql);

    $sql = "CREATE TABLE IF NOT EXISTS images(
            `image_id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			`username` VARCHAR(25) NOT NULL,
            `image` VARCHAR(2000),
            `likes` INT DEFAULT 0,
            `comments` INT DEFAULT 0,
            `date_posted` DATETIME DEFAULT CURRENT_TIMESTAMP)";
    $con->exec($sql);
    //echo "Table 'images' created" . "<br/>";
    
    /* Comments table creation */
    $sql = "USE camagru";
    $con->exec($sql);

    $sql = "CREATE TABLE IF NOT EXISTS comments(
            `com_id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			`username` VARCHAR(25),
            `image_id` INT(6) UNSIGNED,
            `comment` TEXT,
            `date_commented` DATETIME DEFAULT CURRENT_TIMESTAMP)";
    $con->exec($sql);
    //echo "Table 'comments' created" . "<br/>";
    
    /* Likes table creation */
	$sql = "USE camagru";
    $con->exec($sql);

    $sql = "CREATE TABLE IF NOT EXISTS likes(
            `like_id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			`username` VARCHAR(25),
            `image_id` INT(6) UNSIGNED,
            `date_liked` DATETIME DEFAULT CURRENT_TIMESTAMP)";
    $con->exec($sql);
    //echo "Table 'likes' created" . "<br/>";
    
    /* Random users creation */
    $sql = "USE camagru";
    $con->exec($sql);

    $passwd = serialize(hash("whirlpool", "Hello123"));
    $stmt = $con->prepare("INSERT INTO `users` (name, surname, username, email, passwd, token, verified) VALUES ('hello', 'hello', 'hello', 'hello@hello.com', :passwd, '1', 1)");
    $stmt->execute(["passwd" => $passwd]);

    $passwd = serialize(hash("whirlpool", "World123"));
    $stmt = $con->prepare("INSERT INTO `users` (name, surname, username, email, passwd, token, verified) VALUES ('world', 'world', 'world', 'world@world.com', :passwd, '1', 1)");
    $stmt->execute(["passwd" => $passwd]);

    $passwd = serialize(hash("whirlpool", "Blaah123"));
    $stmt = $con->prepare("INSERT INTO `users` (name, surname, username, email, passwd, token, verified) VALUES ('blaah', 'blaah', 'blaah', 'blaah@blaah.com', :passwd, '1', 1)");
    $stmt->execute(["passwd" => $passwd]);

    echo "<script> alert('Camagru database created and tables users, comments & likes added. Random users generated. Redirecting...'); window.location.href='../index.php'; </script>";
}

catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$con = NULL;

?>
