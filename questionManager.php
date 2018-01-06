<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || ($_SESSION['type'] != 'manager' && $_SESSION['type'] != 'editor')){
  header("location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>עריכת שאלות</title>
	<script>

	function show_add_row() {
		document.getElementById('div_add_row').style.display = 'block';
		document.getElementById('div_add_row_button').style.display = 'none';
	}

	function validateInput() {
		var title = document.getElementById('title').value;
		var answer1 = document.getElementById('answer1').value;
		var answer2 = document.getElementById('answer2').value;
		var answer3 = document.getElementById('answer3').value;
		var answer4 = document.getElementById('answer4').value;
		var right_answer = document.getElementById('right_answer').value;
		var category_id = document.getElementById('category_id').value;
		var level_id = document.getElementById('level_id').value;
		
		if (title.length < 10) {
	        alert("יש למלא לפחות 10 תווים בשדה שאלה");
	        return false;
	    }
	    if (answer1.length < 2) {
	        alert("יש למלא לפחות תו אחד בשדה תשובה");
	        return false;
	    }
	    if (answer2.length < 2) {
	        alert("יש למלא לפחות תו אחד בשדה תשובה");
	        return false;
	    }
	    if (answer3.length < 2) {
	        alert("יש למלא לפחות תו אחד בשדה תשובה");
	        return false;
	    }
	    if (answer4.length < 2) {
	        alert("יש למלא לפחות תו אחד בשדה תשובה");
	        return false;
	    }
		
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				if (this.responseText.includes('השאלה נוצרה בהצלחה')) {
					alert("השאלה נוצרה בהצלחה \n" + this.responseText);
					location.reload();
				}
				else {
					alert("שגיאה ביצירת שאלה: \n" + this.responseText);
				}
            }
        };
		
        xmlhttp.open("GET", "addRow.php?table=questions&title=" + title + "&answer1="+answer1+ "&answer2="+answer2+ "&answer3="+answer3+ "&answer4="+answer4 
					+ "&right_answer="+right_answer+ "&category_id="+category_id+ "&level_id="+level_id, true);
        xmlhttp.send();
		
	}
	
	function editText(span, textarea) {
		span.style.display = "none";
		textarea.style.display = "block";
	}

	function deleteRow(id) {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            	if (this.responseText.includes('הרשומה נמחקה')) {
					alert("רשומה " + id + " נמחקה בהצלחה");
					location.reload();
				}
				else {
					alert('שגיאה במחיקת הרשומה \n' + this.responseText);
				}
            }
        };
        xmlhttp.open("GET", "deleteRow.php?table=questions&id=" + id, true);
        xmlhttp.send();
	}

	function editRow(id) {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				if (this.responseText.includes('הרשומה עודכנה')) {
					alert("רשומה " + id + " עודכנה בהצלחה");
					location.reload();
				}
				else {
					alert('שגיאה בעדכון הרשומה \n' + this.responseText);
				}
            }
        };

        var elements = document.getElementsByName(id);
        var params = "";

        for(var i=0;i<elements.length;i++){
        	params += "&p" + (i+1) + "=" + elements[i].value;
        }

        //alert(params);

        xmlhttp.open("GET", "editRow.php?table=questions&id=" + id + params, true);
        xmlhttp.send();
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
	<center>
	<br><br><br>
	<div class="title">עריכת שאלות</div>
	<br>
	<div id="div_add_row" style='float:right; display: none; color:white;'>
		<br>
		שאלה
		<input class="add_row" id='title'>
		תשובה1
		<input class="add_row" id='answer1'>
		תשובה2
		<input class="add_row" id='answer2'>
		תשובה3
		<input class="add_row" id='answer3'>
		תשובה4
		<input class="add_row" id='answer4'>
		תשובה נכונה
		<?php
			$db = include 'database.php';
			// Create connection
			$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
			mysqli_set_charset($conn,'utf8');
			// Check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			}

			$right_answers = "<option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option>";

			// get a list of categories
			$sql = "SELECT *
					FROM categories";
			$result = $conn->query($sql);
			$categories = "";
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$categories .= "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
				}
			}

			// get a list of levels
			$sql = "SELECT id
					FROM questionlevels";
			$result = $conn->query($sql);
			$levels = "";
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$levels .= "<option value=" . $row['id'] . ">" . $row['id'] . "</option>";
				}
			}
		?>
		<select style="height:25px;" id="right_answer"><?php echo $right_answers ?></select>
		קטגוריה
		<select style="height:25px;" id="level_id"><?php echo $categories ?></select>
		רמה
		<select style="height:25px;" id="category_id"><?php echo $levels ?></select>
		<button style="margin-right:10px;" onclick="javascript:validateInput();">הוסף שאלה</button>

	</div>
	<font size="4" color="white">
	<div id="div_add_row_button">
		<div id="right_panel">
			<button class="roundbutton" onclick="javascript:show_add_row();">+</button> 
		</div>
		<div style="float:right; padding: 10px 5px 0px 0px;">
			הוסף שאלה
		</div>
	</div>
	<br><br><br><br>
	<u>טבלת שאלות</u>
	</font>
	<br><br>
	<table class="mainTable">
		<tr>
			<th>שאלה</th>
			<th>תשובה 1</th>
			<th>תשובה 2</th>
			<th>תשובה 3</th>
			<th>תשובה 4</th>
			<th>תשובה נכונה</th>
			<th>קטגוריה</th>
			<th>רמת קושי</th>
			<th>מחיקה</th>	
			<th>עדכון</th>
		</tr>
		<?php
		
		function shortenString($str){
			if (mb_strlen($str) > 50){
				$str = mb_substr($str, 0, 50) . "...";
			}
			return $str;
		}

		// get a list of questions
		$sql = "SELECT *
				FROM questions";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo "<tr>
						<td><textarea name='" . $row['id'] . "' hidden>" . $row['title'] . "</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . shortenString($row['title']) . "</span></td>" . 
						"<td><textarea name='" . $row['id'] . "' hidden>". $row['answer1'] ."</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . $row['answer1'] . "</span></td>" . 
						"<td><textarea name='" . $row['id'] . "' hidden>". $row['answer2'] ."</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . $row['answer2'] . "</span></td>" . 
						"<td><textarea name='" . $row['id'] . "' hidden>". $row['answer3'] ."</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . $row['answer3'] . "</span></td>" . 
						"<td><textarea name='" . $row['id'] . "' hidden>". $row['answer4'] ."</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . $row['answer4'] . "</span></td>" . 
						"<td width='1%;'><select name='" . $row['id'] . "'>" . str_replace(">" . $row['right_answer'], " selected>" . $row['right_answer'], $right_answers) . "</select></td>" . 
						"<td><select name='" . $row['id'] . "' >" . str_replace($row['category_id'], $row['category_id'] . " selected", $categories) . "</select></td>" . 
						"<td><select name='" . $row['id'] . "' >" . str_replace(">" . $row['level_id'] . "<", " selected>" . $row['level_id'] . "<" , $levels) .  "</select></td>" . 
						"<td width='1%;'><button class='minibutton' onclick='javascript:deleteRow(" . $row['id'] . ");'>X</button></td>" .
						"<td width='1%;'><button class='minibutton' onclick='javascript:editRow(" . $row['id'] . ");'>✓</button></td>
					</tr>";
			}
		} else {
			//echo "0 results";
		}
		$conn->close();
	?>
	
	</table>
	<br>
				
</body>
</html>