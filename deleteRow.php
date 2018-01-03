<?php
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || ($_SESSION['type'] != 'manager' && $_SESSION['type'] != 'editor')){
  header("location: login.php");
  exit;
}

$table = $_GET['table'];
// Create connection
$db = include 'database.php';
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
if ($table == "users" || $table == "questions"||$table=="categories") {
	$stmt = $conn->prepare("delete from " . $table . " where id = ?");
	$stmt->bind_param("s", $id_to_delete);
	$id_to_delete = $_GET['id'];
	$stmt->execute();
	$stmt->close();
	$conn->close();
	echo "הרשומה נמחקה";
}
else {
	echo "טעות בטבלה";
}

?>