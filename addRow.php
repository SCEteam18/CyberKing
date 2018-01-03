
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

if ($table == "users") {
					
	$stmt = $conn->prepare("INSERT INTO users (username, password, firstname, lastname, email, type) values (?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("ssssss", $username, $password, $firstname, $lastname, $email, $type);
	
	$username = $_GET['username'];
	$password = password_hash($_GET['password'], PASSWORD_DEFAULT);
	$firstname = $_GET['firstname'];
	$lastname = $_GET['lastname'];
	$email = $_GET['email'];
	$type = $_GET['type'];
	$stmt->execute();
	$stmt->close();
	$conn->close();
	echo "המשתמש נוצר בהצלחה";
}
if ($table == "questions") {
	$stmt = $conn->prepare("INSERT INTO questions (title, answer1, answer2, answer3, answer4, right_answer, category_id, level_id, user_id) values (?, ?, ?, ?, ?, ?, ?, ?, (select id from users where username = ?))");
	$stmt->bind_param("sssssssss", $title, $answer1, $answer2, $answer3, $answer4, $right_answer, $category_id, $level_id, $_SESSION['username']);
	
	$title = $_GET['title'];
	$answer1 = $_GET['answer1'];
	$answer2 = $_GET['answer2'];
	$answer3 = $_GET['answer3'];
	$answer4 = $_GET['answer4'];
	$right_answer = $_GET['right_answer'];
	$category_id = $_GET['category_id'];
	$level_id = $_GET['level_id'];
	//echo $title . ", " . $answer1 . ", " . $answer2 . ", " . $answer3 . ", " .$answer4 . ", " . $right_answer . ", " . $category_id . ", " . $level_id;
	
	$stmt->execute();
	$stmt->close();
	
	$conn->close();
	echo "השאלה נוצרה בהצלחה";
}
if($table=="categories"){
	
	$stmt = $conn->prepare("INSERT INTO categories (name) values (?)");
	$stmt->bind_param("s", $name);

	$name = $_GET['name'];
	$stmt->execute();
	$stmt->close();
	$conn->close();
	echo "הקטגוריה נוצרה בהצלחה";
	
}
else {
	echo "טעות בטבלה";
}

?>