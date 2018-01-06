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
	<title>עריכת משתמשים</title>
	<script>
		
	function show_add_row() {
		document.getElementById('div_add_row').style.display = 'block';
		document.getElementById('div_add_row_button').style.display = 'none';
	}

	function addUser() {
		var username = document.getElementById('txt_username').value;
		var password = document.getElementById('txt_password').value;
		var firstname = document.getElementById('txt_firstname').value;
		var lastname = document.getElementById('txt_lastname').value;
		var email = document.getElementById('txt_email').value;
		var type = document.getElementById('type').value;
		if (validateInput(true, username, password, firstname, lastname, email)) {
			
			var xmlhttp = new XMLHttpRequest();
	        xmlhttp.onreadystatechange = function() {
	            if (this.readyState == 4 && this.status == 200) {
					if (this.responseText.includes('המשתמש נוצר בהצלחה')) {
						alert("המשתמש נוצר בהצלחה");
						location.reload();
					}
					else {
						alert("שגיאה ביצירת משתמש: \n" + this.responseText);
					}
	            }
	        };
	        xmlhttp.open("GET", "addRow.php?table=users&username=" + username + "&password="+password+ "&firstname="+firstname+ "&lastname="+lastname+ "&email="+email + "&type="+type, true);
	        xmlhttp.send();
	        return true;
		}
		return false;
	}

	function validateInput(pwd, username, password, firstname, lastname, email) {
		
		if (firstname.length < 2) {
	        alert("נא למלא לפחות 2 תווים בשדה שם פרטי");
	        return false;
	    }
	    if (lastname.length < 2) {
	        alert("נא למלא לפחות 2 תווים בשדה שם משפחה");
	        return false;
	    }
	    if (username.length < 5) {
	        alert("נא למלא לפחות 5 תווים בשדה שם המשתמש");
	        return false;
	    }
	    if (pwd && password.length < 5) {
	        alert("נא למלא לפחות 5 תווים בשדה של סיסמה");
	        return false;
	    }
	    if (email.length < 5) {
	        alert("נא למלא לפחות 5 תווים בשדה המייל");
	        return false;
	    }
	    else{
	    	if(!validateEmail(email)){
	    		alert("נא לרשום כתובת מייל חוקית");
	        	return false;
	    	}
	    }
	    return true;
	}
	
	function validateEmail(email) {
	    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return regex.test(email);
	}
	
	function editText(span, textarea) {
		span.style.display = "none";
		textarea.style.display = "block";
	}

	function deleteRow(id) {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				alert("רשומה " + id + " נמחקה בהצלחה");
				location.reload();
            }
        };
        xmlhttp.open("GET", "deleteRow.php?table=users&id=" + id, true);
        xmlhttp.send();
	}

	function editRow(id) {
		var username = document.getElementById('user_' + id).value;
		var password = document.getElementById('pwd_' + id).value;
		var firstname = document.getElementById('fname_' + id).value;
		var lastname = document.getElementById('lname_' + id).value;
		var email = document.getElementById('email_' + id).value;

		var validate_password = true;
		if (password.length == 0) {
			validate_password = false;
		}
		if (validateInput(validate_password, username, password, firstname, lastname, email)) {

			var xmlhttp = new XMLHttpRequest();
	        xmlhttp.onreadystatechange = function() {
	            if (this.readyState == 4 && this.status == 200) {
	            	if (this.responseText.includes("הרשומה עודכנה")) {
						alert("רשומה " + id + " עודכנה בהצלחה");
						location.reload();
					}
					else {
						alert(this.responseText);
					}
	            }
	        };

	        var elements = document.getElementsByName(id);
	        var params = "";

	        for(var i=0;i<elements.length;i++){
	        	params += "&p" + (i+1) + "=" + elements[i].value;
	        }
	        
	        //alert(params);

	        xmlhttp.open("GET", "editRow.php?table=users&id=" + id + params, true);
	        xmlhttp.send();
    	}
    	else {
    		alert('הכנס סיסמה ארוכה יותר');
    	}
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
	<div class="title">עריכת עובדי האתר</div>
	<br>
	<div id="div_add_row" style='float:right; display: none; color:white;'>
		<br>
		שם משתמש
		<input class="add_row" id='txt_username'>
		סיסמה
		<input class="add_row" id='txt_password' type='password'>
		שם פרטי
		<input class="add_row" id='txt_firstname'>
		שם משפחה
		<input class="add_row" id='txt_lastname'>
		מייל
		<input class="add_row" id='txt_email'>
		<select style="height:25px;" id="type"><option value="editor" selected>editor</option><option value="manager">manager</option><option value="secretary">secretary</option></select>
		<button style="margin-right:10px;" onclick="javascript:addUser();">הוסף עובד</button>
	</div>
	<font size="4" color="white">
	<div id="div_add_row_button">
		<div id="right_panel">
			<button class="roundbutton" onclick="javascript:show_add_row();">+</button> 
		</div>
		<div style="float:right; padding: 10px 5px 0px 0px;">
			הוסף עובד
		</div>
	</div>
	<br><br><br><br>
	<u>טבלת העובדים</u>
	</font>
	<br><br>
	<table class="mainTable">
		<tr>
			<th>שם משתמש</th>
			<th>בחירת סיסמה</th>
			<th>מייל</th>
			<th>שם פרטי</th>
			<th>שם משפחה</th>
			<th>סוג משתמש</th>
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

		$db = include 'database.php';
		// Create connection
		$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
		mysqli_set_charset($conn,'utf8');
		// Check connection
		if ($conn->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT *
				FROM Users
				WHERE type <> 'player'";
		$result = $conn->query($sql);
		
		$permissions = "<option value='manager'>manager</option><option value='editor'>editor</option><option value='secretary'>secretary</option>";

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo "<tr>
						<td><textarea id='user_" . $row['id'] . "' name='" . $row['id'] . "' hidden>" .  $row['username'] . "</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . $row['username'] . "</span></td>" . 
						"<td><input id='pwd_" . $row['id'] . "' name='" . $row['id'] . "'></input></td>" . 
						"<td><textarea id='email_" . $row['id'] . "' name='" . $row['id'] . "' hidden>". $row['email'] ."</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . $row['email'] . "</span></td>" . 
						"<td><textarea id='fname_" . $row['id'] . "' name='" . $row['id'] . "' hidden>". $row['firstname'] ."</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . $row['firstname'] . "</span></td>" . "
						<td><textarea id='lname_" . $row['id'] . "' name='" . $row['id'] . "' hidden>". $row['lastname'] ."</textarea><span onclick='javascript:editText(this, this.parentNode.firstChild);'>" . $row['lastname'] . "</span></td>" . 
						"<td><select name='" . $row['id'] . "'>" . str_replace(">" . $row['type'], " selected>" . $row['type'], $permissions) . "</select></td>" .
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