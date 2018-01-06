<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || !isset($_SESSION['type']) || ($_SESSION['type'] != 'editor' && $_SESSION['type'] != 'manager')) {
  header("location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>ברוכים הבאים</title>
	<script type="text/javascript">

		function searchPlayer() {

			var txt_username = document.getElementById('txt_username').value;
			var txt_firstname = document.getElementById('txt_firstname').value;
			var txt_lastname = document.getElementById('txt_lastname').value;
			var txt_email = document.getElementById('txt_email').value;
			var txt_resultnum = document.getElementById('txt_resultnum').value;
			
			if (txt_username.length < 2 && txt_firstname.length < 2  && 
				txt_lastname.length < 2  && txt_email.length < 2) {
				alert ("הכנס לפחות שדה אחד בשדות החיפוש");
				return;
			}

			var xmlhttp = new XMLHttpRequest();
	        xmlhttp.onreadystatechange = function() {
	            if (this.readyState == 4 && this.status == 200) {
					if (this.responseText.includes('success')) {
						var response = this.responseText.split("!");
						var tables = document.getElementsByTagName("table");
						document.getElementById("category_select").style.display = 'none';

						for (var i = 0; i < tables.length; i++) {
							tables[i].style.display = 'none';
						}

						var search_table = document.getElementById("tbl_search");
						search_table.style.display = '';
						var rows = search_table.rows;
					  	var i = 1;
					  	while (i < rows.length) {
					    	search_table.deleteRow(i++);
					  	}
					  	
						for (var i = 1; i < response.length; i++) {
							var data = response[i].split(";");
							if (data[0].length > 1) {
								var row = search_table.insertRow(1);
								var cell1 = row.insertCell(0);
								var cell2 = row.insertCell(1);
								var cell3 = row.insertCell(1);
								var cell4 = row.insertCell(1);
								var cell5 = row.insertCell(1);
								
								cell1.innerHTML = data[0];
								cell2.innerHTML = data[1];
								cell3.innerHTML = data[2];
								cell4.innerHTML = data[3];
								cell5.innerHTML = data[4];
							}
						}
					}
					else {
						alert("שגיאה בחיפוש: \n" + this.responseText);
					}
	            }
	        };
			
	        xmlhttp.open("GET", "playerSearch.php?username="+ txt_username + "&firstname=" + txt_firstname + "&lastname="+txt_lastname+ "&email="+txt_email+ "&resultnum="+txt_resultnum, true);
	        xmlhttp.send();
		}

		function changeHighcores() {
			var category_list = document.getElementById("category_list");
			var tables = document.getElementsByTagName("table");
			//hide all high scores
			for (var i = 0; i < tables.length; i++) {
				tables[i].style.display = 'none';
			}
			//show the selected highscore
			if (category_list.value == 'all') {
				document.getElementById('tbl_all_categories').style.display = '';
			}
			else {
				document.getElementById('tbl' + category_list.value).style.display = '';
			}
		}

		function show_search() {
			document.getElementById('div_search_row').style.display = 'block';
			document.getElementById('div_search_row_button').style.display = 'none';
		}

	</script>
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
	<div class="subtitle">טבלאות שחקנים</div>
	<br>
	<div id="invisible_container" style="width:100%;">
		<div id="div_search_row" style='display: none; color:white; float:right;'>
			
			שם משתמש
			<input class="add_row" id='txt_username'>
			שם פרטי
			<input class="add_row" id='txt_firstname'>
			שם משפחה
			<input class="add_row" id='txt_lastname'>
			מייל
			<input class="add_row" id='txt_email'>
			כמות תוצאות
			<input class="add_row" id='txt_resultnum' value="10">
			<button style="margin-right:10px;" onclick="javascript:searchPlayer();">חפש שחקן</button>
		</div>
		<div id="div_search_row_button">
			<div id="right_panel">
				<button class="roundbutton" onclick="javascript:show_search();">?</button> 
			</div>
			<div style="float:right; padding: 10px 5px 0px 0px; color: white;">
				חיפוש שחקן
			</div>
		</div>
		<br><br><br><br>
		<div id="category_select">
			<font color="white">
			<?php
			$db = include 'database.php';
			// Create connection
			$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
			mysqli_set_charset($conn,'utf8');
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
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

			// best daily player
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

	    	?>
			<br><br>
			בחר קטגוריה:
	    	</font>
			<select id="category_list" onchange="javascript:changeHighcores();"><option value="all">כל הקטגוריות</option><?php echo $categories; ?></select>
			<br><br>
		</div>

		<?php
		// create an invisible table for each category highscore
		for($i = 0; $i < $cat_array_length; $i++)
		{
		    echo "<table class='mainTable' id='tbl" . $category_ids[$i] . "' style='display: none;'>
			<tr>
				<th>מקום</th>
				<th>משתמש</th>
				<th>שם פרטי</th>
				<th>שם משפחה</th>
				<th>מייל</th>
				<th>ניקוד</th>
			</tr>";
			
			$sql = "SELECT u.username, cr.max_score, u.firstname, u.lastname, u.email
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
			    	if ($place > 10) {
		    			break;
		    		}
			    	elseif ($place <= 10) {
			        	echo "<tr><td>" . $place. "</td><td style='width:150px;'>" . $row["username"] . "</td>" . 
			        		"<td>" . $row["firstname"] . "</td>" . 
			        		"<td>" . $row["lastname"] . "</td>" . 
			        		"<td>" . $row["email"] . "</td>" . 
			        		"<td>" . $row["max_score"] . "</td></tr>";
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

		<table class="mainTable" id="tbl_search" style="display: none;">
			<tr>
				<th>משתמש</th>
				<th>שם פרטי</th>
				<th>שם משפחה</th>
				<th>מייל</th>
				<th>ניקוד</th>
			</tr>
		</table>

		<table class="mainTable" id="tbl_all_categories">
			<tr>
				<th>מקום</th>
				<th>משתמש</th>
				<th>שם פרטי</th>
				<th>שם משפחה</th>
				<th>מייל</th>
				<th>ניקוד</th>
			</tr>
			<?php
			
			$sql = "SELECT u.username, sum(cr.max_score) as max_score, cr.max_score, u.firstname, u.lastname, u.email
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
		    		if ($place > 10) {
		    			break;
		    		}
			    	elseif ($place <= 10) {
			        	echo "<tr><td>" . $place. "</td><td style='width:150px;'>" . $row["username"] . "</td>" . 
			        		"<td>" . $row["firstname"] . "</td>" . 
			        		"<td>" . $row["lastname"] . "</td>" . 
			        		"<td>" . $row["email"] . "</td>" . 
			        		"<td>" . $row["max_score"] . "</td></tr>";
			    	}
			    	$place++;
			    }
			} else {
			    //echo "0 results";
			}

			$conn->close();
    	?>
    	
		</table>
		<br>
		</font>
	</div>
	</div>
</body>
</html>