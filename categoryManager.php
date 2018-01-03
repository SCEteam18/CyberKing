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
	<title>עריכת קטגוריות</title>
	<script>
	function validateInput() {
		var name = document.getElementById('name').value;
		
		if (name.length < 2) {
	        alert("שם הקטגוריה קצר מדי");
	        return false;
	    }
	   
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				if (this.responseText.includes('הקטגוריה נוצרה בהצלחה')) {
					alert("הקטגוריה נוצרה בהצלחה \n" + this.responseText);
					location.reload();
				}
				else {
					alert("שגיאה ביצירת קטגוריה: \n" + this.responseText);
				}
            }
        };
		
        xmlhttp.open("GET", "addRow.php?table=categories&name=" + name , true);
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
        xmlhttp.open("GET", "deleteRow.php?table=categories&id=" + id, true);
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

        xmlhttp.open("GET", "editRow.php?table=categories&id=" + id + params, true);
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
	<div class="title">עריכת קטגוריות</div>
	<br>
	<div style='float:center;'>
		<br>
		קטגוריה
		<input class="add_row" id='name'>
		<br>
		
		<?php
			$db = include 'database.php';
			// Create connection
			$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
			mysqli_set_charset($conn,'utf8');
			// Check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			}

		?>
		
		<br>
		<button style="margin-right:10px;" onclick="javascript:validateInput();">הוסף קטגוריה</button>
		<br>
	</div>
	<br>
	<font size="4" color="white">
		<u>טבלת קטגוריות</u>
		</font>
	<br><br>
	
	<table class="mainTable">
		<tr>
			<th>קטגוריה</th>
			<th>מספר קטגוריה</th>
			<th>מחיקה</th>	
			<th>עדכון</th>
		</tr>
		<?php
		
		function shortenString($str){
			if (strlen($str) > 50){
				$str = substr($str, 0, 50) . "...";
			}
			return $str;
		}

		// get a list of questions
		$sql = "SELECT *
				FROM categories";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo "<tr>
						<td><textarea name='" . $row['id'] . "' hidden>" . $row['name'] . "</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . shortenString($row['name']) . "</span></td>" .
                        "<td width='1%;'><span>". $row['id'] ."</span></td>" .						
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