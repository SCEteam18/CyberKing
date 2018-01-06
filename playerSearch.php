
<?php
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || ($_SESSION['type'] != 'manager' && $_SESSION['type'] != 'secretary')) {
  header("location: login.php");
  exit;
}

// Create connection
$db = include 'database.php';
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT u.username, u.firstname, u.lastname, u.email, p.score
		FROM users u
		JOIN players p ON p.user_id = u.id AND u.enabled = 1 AND u.type = 'player'
			AND u.username like ? 
			AND u.firstname like ?
			AND u.lastname like ?
			AND u.email like ?
";

$stmt = $conn->prepare($sql);




$username = "%{$_GET['username']}%";
$firstname = "%{$_GET['firstname']}%";
$lastname = "%{$_GET['lastname']}%";
$email = "%{$_GET['email']}%";
$resultnum = $_GET['resultnum'];
if (is_numeric($resultnum)) {
	$resultnum = intval($resultnum);
	if ($resultnum == 0) {
		$resultnum = 10;
	}
}
else {
	$resultnum = 10;
}

$stmt->bind_param("ssss", $username, $firstname, $lastname, $email);
$stmt->execute();
$stmt->bind_result($username_result, $fistname_result, $lastname_result, $email_result, $score_result);
$output = "";
while ($stmt->fetch()) {
        $output .= $username_result .";" . $fistname_result .";" . $lastname_result .";" . $email_result .";" . $score_result ."!";
}

$stmt->close();
$conn->close();
echo "success!" . $output;

?>