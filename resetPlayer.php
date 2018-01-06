<?php
$db = include 'database.php';
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$player_id = 3;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	$player_id = $_GET['id'];
}

$sql = "update players set startDate = now(), question_num = 0, score = 0 where id = $player_id";
$result = $conn->query($sql);
$sql = "delete from playerquestions where player_id = $player_id";
$result = $conn->query($sql);


?>