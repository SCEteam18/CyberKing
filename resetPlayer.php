<?php
$db = include 'database.php';
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	$sql = "update players set startDate = now(), question_num = 0, score = 0 where id = " . $_GET['id'];
}
else {
	$sql = "update players set startDate = now(), question_num = 0, score = 0 where id = 3";
}

$result = $conn->query($sql);

?>