<?php
$db = include 'database.php';
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


// select player's last question
$sql = "update players set startDate = '2017-01-01 00:00' where id = 3";

$result = $conn->query($sql);

?>