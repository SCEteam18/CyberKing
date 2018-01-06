
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
	if(isset($_GET['p1']) && isset($_GET['p2']) && isset($_GET['p3']) && isset($_GET['p4']) && 
		isset($_GET['p5']) && isset($_GET['p6']) && isset($_GET['p7']) && isset($_GET['p8']) && 
		isset($_GET['id']) && 
		$_GET['p1'] != "" && $_GET['p2'] != "" && $_GET['p3'] != "" && $_GET['p4'] != "" && 
		$_GET['p5'] != "" && $_GET['p6'] != "" && $_GET['p7'] != "" && $_GET['p8'] != "" && $_GET['id'] != "") {

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
}
elseif ($table == "categories") {
	
	if(isset($_GET['p1']) && $_GET['p1'] != "") {
		$stmt = $conn->prepare("update categories set name = ? where id = ?");
		$stmt->bind_param("ss", $name, $id_to_edit);
		$name = $_GET['p1'];
		$id_to_edit = $_GET['id'];
		$stmt->execute();
		$stmt->close();
		$conn->close();
		echo "הרשומה עודכנה";
	}
}
elseif ($table == "users") {
	
	if(isset($_GET['p1'])  && isset($_GET['p3']) && isset($_GET['p4']) && 
		isset($_GET['p5']) && isset($_GET['p6']) && isset($_GET['id']) && 
		$_GET['p1'] != "" && $_GET['p3'] != "" && $_GET['p4'] != "" && 
		$_GET['p5'] != "" && $_GET['p6'] != "" && $_GET['id'] != "") {

		//check if to change password
		if (isset($_GET['p2']) && $_GET['p2'] != "") {
			echo "zzz\n";
			$stmt = $conn->prepare("update users set username = ?, password = ?, email = ?, firstname = ?, lastname = ?, type = ? where id = ?");
			$stmt->bind_param("sssssss", $username, $password, $email, $firstname, $lastname, $type, $id_to_edit);
			$password = password_hash($_GET['p2'], PASSWORD_DEFAULT);
			echo "\npassword:" . $_GET['p2'] .";\n";
		}
		else {
			$stmt = $conn->prepare("update users set username = ?, email = ?, firstname = ?, lastname = ?, type = ? where id = ?");
			$stmt->bind_param("ssssss", $username, $email, $firstname, $lastname, $type, $id_to_edit);
		}
		
		$username = $_GET['p1'];
		$email = $_GET['p3'];
		$firstname = $_GET['p4'];
		$lastname = $_GET['p5'];
		$type = $_GET['p6'];
		$id_to_edit = $_GET['id'];

		$stmt->execute();
		$stmt->close();
		$conn->close();
		echo "הרשומה עודכנה";
	}
}
elseif ($table=="questionlevels") {
	
	$stmt = $conn->prepare("UPDATE questionlevels set score = ? where id = ?");
	$stmt->bind_param("ss", $score, $id_to_edit);
	if (empty($_GET['p2'])) {
	    $score = $_GET['p1'];
	}
	else {
		$score = $_GET['p2'];	
	}
	if (is_numeric($score)) {
	    $id_to_edit = $_GET['id'];
	    $stmt->execute();
	    $stmt->close();
	    $conn->close();
	    echo "הרשומה עודכנה";
	}
	else{
		echo "שגיאה בקלט. אנא הכנס רק מספרים";
	}
	
}
else {
	echo "טעות בטבלה";
}

?>