<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || !isset($_SESSION['type']) || ($_SESSION['type'] != 'editor' && $_SESSION['type'] != 'manager')){
  header("location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>ברוכים הבאים</title>
</head>
<body dir="rtl">
	<div style="float:left; position: relative;">
		<font size="4" color="white">
		ברוכים הבאים,
		<?php 
			echo $_SESSION['firstname'] . " " . $_SESSION['lastname'];
		?>
		</font>
		<button onclick="window.location='admin.php';" style="margin-right: 15px;">תפריט ראשי</button>
		<button onclick="window.location='logout.php';">יציאה</button>
	</div>
	<br><br><br><br>
	<center>
	<div class="title">CyberKing</div>
	<br>
	<div class="subtitle">רשימת שיאים</div>
	<br>
	<div class="container" style="width: 700px;">
		
				<font size="4">
				<u>טבלת שיאים יומית</u>
				</font>
				<br><br>
				<table class="mainTable">
					<tr>
						<th>מקום</th>
						<th>משתמש</th>
						<th>ניקוד</th>
					</tr>
					<?php
					$db = include 'database.php';
					// Create connection
					$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
					mysqli_set_charset($conn,'utf8');
					// Check connection
					if ($conn->connect_error) {
					  die("Connection failed: " . $conn->connect_error);
					}
					$sql = "SELECT u.username, p.score
							FROM Users u
							JOIN players p on p.user_id = u.id
							LEFT JOIN categoryrecords cr on cr.player_id = p.id
							
							ORDER BY p.score DESC 
							LIMIT 10";
					$result = $conn->query($sql);
					$place = 1;
					if ($result->num_rows > 0) {
					    // loop each row of query to find both top ten and the current user's place
					    while($row = $result->fetch_assoc()) {
					    	echo "<tr><td>" . $place++ . "</td><td style='width:150px;'>" . $row["username"] . "</td><td>" . $row["score"] . "</tr>";
					    	
					    }
					} else {
					    //echo "0 results";
					}
					$conn->close();
	        	?>
	        	
				</table>
		<br><br>
	</div>
	</div>
</body>
</html>