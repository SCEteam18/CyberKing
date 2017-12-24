<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || !isset($_SESSION['type']) || ($_SESSION['type'] != 'player' && $_SESSION['type'] != 'manager')){
  header("location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script>

function askDelete() {
	if (confirm('אתה בטוח שאתה רוצה למחוק את עצמך?')) {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				
            	if (this.responseText.includes('הרשומה נמחקה')) {
					alert("רשומה  נמחקה הצלחה");
					window.location='logout.php';
				}
				else {
					alert('שגיאה במחיקת הרשומה \n' + this.responseText);
				}
            }
        };
		
		
        xmlhttp.open("GET", "disableUser.php", true);
        xmlhttp.send();
	}


	}
	
	</script>
	<title>ברוכים הבאים</title>
</head>
<body dir="rtl">

	<center>
	<div class="title">CyberKing</div>
	<div class="subtitle">חידון הסייבר הארצי</div>
	<br>
	<div class="container" style="width: 700px;">
		<div id="wrapper">
			<div id="right_panel">
				<font size="4">
				ברוכים הבאים,
				<br>
				<?php 
					echo $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "<br><br>";
				?>
				</font>
				<button onclick="window.location='trivia.php';">שחק בטריוויה</button><br>
				<button onclick="javascript:askDelete();">הסר את עצמך</button><br>
				<button onclick="window.location='logout.php';">יציאה</button><br>
				<br><br>
				<u>הוראות משחק</u>
				<ul>
					<li><span>ניתן לשחק במשחק אחד ביום.</span></li>
					<li><span>התחלת משחק תאפס את הניקוד של היום הקודם.</span></li>
					<li><span>כל שאלה תיתן בין 100 ל-200 נקודות (תלוי ברמה).</span></li>
					<li><span>כל דקה שעוברת מורידה את הניקוד ב-10 נקודות.</span></li>
				</ul>
			</div>
			<div id="left_panel">
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
							JOIN Players p on p.user_id = u.id
							ORDER BY p.score DESC";
					$result = $conn->query($sql);
					$place = 1; // the place of each user in the list
					$user_place = 0; // the place of the logged in user
					$user_score = 0; // the score of the logged in user
					if ($result->num_rows > 0) {
					    // loop each row of query to find both top ten and the current user's place
					    while($row = $result->fetch_assoc()) {
					    	if ($row["username"] == $_SESSION['username']) {
					    		$user_place = $place;
					    		$user_score = $row['score'];
					    		if ($place > 10) {
					    			break;
					    		}
					    	}
					    	if ($place <= 10) {
					        	echo "<tr><td>" . $place++ . "</td><td style='width:150px;'>" . $row["username"] . "</td><td>" . $row["score"] . "</tr>";
					    	}
					    }
					} else {
					    //echo "0 results";
					}
					$conn->close();
	        	?>
	        	
				</table>
				<br>
				המיקום הנוכחי שלך:
				<?php echo $user_place; ?>
				<br>
				ניקוד נוכחי:
				<?php echo $user_score; ?>
				<br>
				השיא שלך:
				<?php echo $_SESSION['max_score']; ?>
		    </div>
		</div>
		<br><br>
	</div>
	</div>
	
</body>
</html>