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
	function changeHiscores() {

		var category_list = document.getElementById("category_list");
		var tables = document.getElementsByTagName("table");
		//hide all high scores
		for (var i = 0; i < tables.length; i++) {
			tables[i].style.display = 'none';
		}
		//show the selected highscore
		if (category_list.value == 'all') {
			document.getElementById('tbl_all_categories').style.display = 'block';
		}
		else {
			document.getElementById('tbl' + category_list.value).style.display = 'block';
		}
	}

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
				<?php
					$db = include 'database.php';
					// Create connection
					$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
					mysqli_set_charset($conn,'utf8');
					// Check connection
					if ($conn->connect_error) {
					  die("Connection failed: " . $conn->connect_error);
					}
					//check if user played already today
					$sql = "SELECT p.id, p.question_num, p.startDate,
								(select max(pq.id)
									from PlayerQuestions pq
									where pq.player_id = p.id)
								as max_id
							FROM Users u
							JOIN Players p on p.user_id = u.id
							where u.username = ?";
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("s", $username);
					$username = $_SESSION['username'];
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($player_id, $question_num, $startDate, $question_id);
					$stmt->fetch();
					$stmt->close();
					$_SESSION['player_id'] = $player_id;
					$startDate = date_create_from_format('Y-m-d H:i:s', $startDate);
					$now = getdate();

					//if player already played today
					if ($startDate->format("d") == $now['mday'] && $startDate->format("m") == $now['mon'] && $startDate->format("Y") == $now['year'] && $question_num > $db['questions_num_per_game']) {
						echo "<button style='background-color:#777;' onclick=\"alert('אפשר לשחק רק משחק אחד ביום');\">שחק בטריוויה</button><br>";
					}
					else {
						echo "<button onclick=\"window.location='trivia.php';\">שחק בטריוויה</button><br>";
					}

					// get a list of categories
					$sql = "SELECT *
							FROM categories";
					$result = $conn->query($sql);
					$categories = "";
					$category_ids = array();
					$cat_array_length = $result->num_rows;
					if ($cat_array_length > 0) {
						while($row = $result->fetch_assoc()) {
							$category_ids[] = $row['id'];
							$categories .= "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
						}
					}

				?>
				<button onclick="javascript:askDelete();">הסר את עצמך</button><br>
				<button onclick="window.location='logout.php';">יציאה</button><br>
				<br><br>
				<div class="container" style="width: 100%;">
				<u>הוראות משחק</u>
					<font size="2">
						<ul>
							<li><span>ניתן לשחק במשחק אחד ביום.</span></li>
							<li><span>שאלות קשות יותר נותנות יותר ניקוד.</span></li>
							<li><span>כל דקה שעוברת מורידה את הניקוד ב-10 נקודות.</span></li>
						</ul>
					</font>
				</div>
			</div>
			<div id="left_panel">
				<font size="4">
				<u>שיאים של כל הזמנים</u>
				</font>
				<br><br>
				<font size="2">
				בחר קטגוריה:
				<select id="category_list" onchange="javascript:changeHiscores();"><option value="all">כל הקטגוריות</option><?php echo $categories; ?></select>
				<br><br>

				<?php
				// create an invisible table for each category highscore
				for($i = 0; $i < $cat_array_length; $i++)
				{
				    echo "<table class='mainTable' id='tbl" . $category_ids[$i] . "' style='display: none;'>
					<tr>
						<th>מקום</th>
						<th>משתמש</th>
						<th>ניקוד</th>
					</tr>";
					
					$sql = "SELECT u.username, cr.max_score
							FROM CategoryRecords cr
							JOIN Players p on cr.player_id = p.id
							join users u on p.user_id = u.id
							where u.enabled <> 0 and cr.category_id = " . $category_ids[$i] . "
							ORDER BY max_score DESC";
					$result = $conn->query($sql);
					$place = 1; // the place of each user in the list
					if ($result->num_rows > 0) {
					    // loop each row of query to find both top ten and the current user's place
					    while($row = $result->fetch_assoc()) {
					    	if ($row["username"] == $_SESSION['username']) {
					    		echo "<tr><td><b>" . $place . "</b></td><td style='width:150px;'><b>" . $row["username"] . "</b></td><td><b>" . $row["max_score"] . "</b></td></tr>";
					    		if ($place > 10) {
					    			break;
					    		}
					    	}
					    	elseif ($place <= 10) {
					        	echo "<tr><td>" . $place. "</td><td style='width:150px;'>" . $row["username"] . "</td><td>" . $row["max_score"] . "</tr>";
					    	}
					    	$place++;
					    }
					} else {
					    //echo "0 results";
					}
					?>
					</table>
				<?php
				}
				?>

				<table class="mainTable" id="tbl_all_categories">
					<tr>
						<th>מקום</th>
						<th>משתמש</th>
						<th>ניקוד</th>
					</tr>
					<?php
					
					$sql = "SELECT u.username, sum(cr.max_score) as max_score
							FROM CategoryRecords cr
							JOIN Players p on cr.player_id = p.id
							join users u on p.user_id = u.id
							where u.enabled <> 0
							group by p.id
							ORDER BY max_score DESC";
					$result = $conn->query($sql);
					$place = 1; // the place of each user in the list
					if ($result->num_rows > 0) {
					    // loop each row of query to find both top ten and the current user's place
					    while($row = $result->fetch_assoc()) {
					    	if ($row["username"] == $_SESSION['username']) {
					    		echo "<tr><td><b>" . $place . "</b></td><td style='width:150px;'><b>" . $row["username"] . "</b></td><td><b>" . $row["max_score"] . "</b></td></tr>";
					    		if ($place > 10) {
					    			break;
					    		}
					    	}
					    	elseif ($place <= 10) {
					        	echo "<tr><td>" . $place. "</td><td style='width:150px;'>" . $row["username"] . "</td><td>" . $row["max_score"] . "</tr>";
					    	}
					    	$place++;
					    }
					} else {
					    //echo "0 results";
					}
	        	?>
	        	
				</table>
				<br>
			</font>
			<?php

				$sql = "SELECT u.username, p.score
						FROM Players p
						join users u on p.user_id = u.id
						where p.score > 0 and u.enabled <> 0 and date(p.startDate) = date(SUBDATE(NOW(),1))
						ORDER BY p.score DESC
						limit 1";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
				    // loop each row of query to find both top ten and the current user's place
				    $found_winner = 0;
				    while($row = $result->fetch_assoc()) {
				    	echo "המצטיין היומי הוא " . $row['username'] . " עם ניקוד: " . $row['score'];
				    	$found_winner = 1;
				    	break;
				    }

				    if ($found_winner == 0) {
				    	echo "אין מצטיין יומי כי אף שחקן לא שיחק אתמול!";
				    }
				}
				else {
					echo "אין מצטיין יומי כי אף שחקן לא שיחק אתמול!";
				}
				$conn->close();
				?>
		    </div>
		</div>
		<br><br><br>
	</div>
</body>
</html>