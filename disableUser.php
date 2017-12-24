

<?php 

header('Content-Type: text/html; charset=utf-8');
session_start();
$db = include 'database.php';
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

	$stmt = $conn->prepare("UPDATE users SET enabled=0 WHERE username=?");
	
	$stmt->bind_param("s", $id_to_delete);
	$id_to_delete = $_SESSION['username'];
	$stmt->execute();
	$stmt->close();
	$conn->close();
	echo "הרשומה נמחקה";




?>


