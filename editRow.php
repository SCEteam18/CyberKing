
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

if ($table == "questions") {

	$stmt = $conn->prepare("update questions set level_id = ?, category_id = ?, title = ?, answer1 = ?, answer2 = ?, answer3 = ?, answer4 = ?, right_answer = ? where id = ?");
	$stmt->bind_param("sssssssss", $level_id, $category_id, $title, $answer1, $answer2, $answer3, $answer4, $right_answer, $id_to_edit);
	$title = $_GET['p1'];
	$answer1 = $_GET['p2'];
	$answer2 = $_GET['p3'];
	$answer3 = $_GET['p4'];
	$answer4 = $_GET['p5'];
	$right_answer = $_GET['p6'];
	$category_id = $_GET['p7'];
	$level_id = $_GET['p8'];
	$id_to_edit = $_GET['id'];
	$stmt->execute();
	$stmt->close();
	$conn->close();
	echo "הרשומה עודכנה";
}
if ($table == "categories") {
	if($_GET['p1']!=""){
     
	$stmt = $conn->prepare("update categories set name = ? where id = ?");
	$stmt->bind_param("ss", $name, $id_to_edit);
	$name=$_GET['p1'];
	$id_to_edit = $_GET['id'];
	$stmt->execute();
	$stmt->close();
	$conn->close();
	echo "הרשומה עודכנה";
	}
}
else {
	echo "טעות בטבלה";
}

?>