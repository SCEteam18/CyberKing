<?php
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || ($_SESSION['type'] != 'manager' && $_SESSION['type'] != 'editor')) {
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
if ($table == "users" || $table == "questions" || $table=="categories") {
	$stmt = $conn->prepare("delete from " . $table . " where id = ?");
	$stmt->bind_param("s", $id_to_delete);
	$id_to_delete = $_GET['id'];
	$stmt->execute();
	$stmt->close();
	$conn->close();
	echo "הרשומה נמחקה";
}
elseif ($table == "questionlevels") {
	
	$stmt = $conn->prepare("update questions set level_id = -1 where level_id = ?");
	$stmt2 = $conn->prepare("delete from " . $table . " where id = ?");
	$stmt3 = $conn->prepare("update questionlevels set id=id-1 where id > ?");
	
	$stmt->bind_param("s", $id_to_edit);
	$stmt2->bind_param("s", $id_to_delete);
	$stmt3->bind_param("s", $id_to_delete);
	
	$id_to_edit = $_GET['id'];
	$id_to_delete = $_GET['id'];
	
	$stmt->execute();
	$stmt->close();
	$stmt2->execute();
	$stmt2->close();
	$stmt3->execute();
	$stmt3->close();
	
	$result = mysqli_query($conn,"SELECT MAX(id) FROM questionlevels");
    $row = mysqli_fetch_row($result);
    $last_id = $row[0];
	
	$stmt4 = $conn->prepare("ALTER TABLE questionlevels AUTO_INCREMENT = $last_id");
	$stmt4->execute();
	$stmt4->close();
	
	$conn->close();
	
	echo "הרשומה נמחקה";
	
}
else {
	echo "טעות בטבלה";
}

?>