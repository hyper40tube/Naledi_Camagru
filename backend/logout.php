<?PHP

session_start();

session_destroy();

echo "<script> alert('You have been logged out.'); location.href='../index.php'; </script>";

?>