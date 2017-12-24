<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript">
    function errorMessage(msg){
      document.getElementById('error_msg').innerText = msg;
    }

    function validateForm() {

	    var firstname = document.forms["registerForm"]["firstname"].value;
	    var lastname = document.forms["registerForm"]["lastname"].value;
	    var username = document.forms["registerForm"]["username"].value;
	    var password = document.forms["registerForm"]["password"].value;
	    var email = document.forms["registerForm"]["email"].value;
	    var captcha = document.forms["registerForm"]["captcha"].value;

	    if (firstname.length < 2) {
	        errorMessage("נא למלא לפחות 2 תווים בשדה שם פרטי");
	        return false;
	    }
	    if (lastname.length < 2) {
	        errorMessage("נא למלא לפחות 2 תווים בשדה שם משפחה");
	        return false;
	    }
	    if (username.length < 5) {
	        errorMessage("נא למלא לפחות 5 תווים בשדה שם המשתמש");
	        return false;
	    }
	    if (password.length < 5) {
	        errorMessage("נא למלא לפחות 5 תווים בשדה של סיסמה");
	        return false;
	    }
	    if (email.length < 5) {
	        errorMessage("נא למלא לפחות 5 תווים בשדה המייל");
	        return false;
	    }
	    else{
	    	if(!validateEmail(email)){
	    		errorMessage("נא לרשום כתובת מייל חוקית");
	        	return false;
	    	}
	    }

	    if (captcha.length < 1) {
	        errorMessage("נא הכנס קוד אימות");
	        return false;
	    }
	    return true;
	}

	function validateEmail(email) {
	    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return regex.test(email);
	}
  	</script>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>הרשמה</title>
</head>
<body>
<center>
<div class="title">הרשמה</div>
<div class="container">
	<form action="register.php" onsubmit="return validateForm();" method="post" name="registerForm">
	  <input type="text" name="firstname" placeholder="שם פרטי"/>
	  <input type="text" name="lastname" placeholder="שם משפחה"/>
	  <input type="text" name="username" placeholder="שם משתמש"/>
	  <input type="password" name="password" placeholder="סיסמה"/>
	  <input type="text" name="email" placeholder="כתובת מייל"/>
	  <img id="captcha_field" alt="CAPTCHA" />
	  <input type="text" id="captcha" name="captcha" placeholder="הכנס את הקוד הרשום למעלה"/>
	  <button style="width: 100%;">הרשם</button>
	  <div id="error_msg" style="color: red;"></div>
	  <p class="message">רשום כבר? <a href="login.php">התחבר</a></p>
	</form>
</div>
</body>
</html>

<?php
session_start();
include("captcha.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
	//form validation in server side
	if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])
 		&& !empty($_POST['email']) && strlen($_POST['username']) > 4 && strlen($_POST['email']) > 4 && strlen($_POST['firstname']) > 2 && strlen($_POST['lastname']) > 2 && strlen($_POST['password']) > 4  
 		&& filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['captcha']))
	{
		//check user's captcha
		if ((empty($_SESSION) || empty($_SESSION['type']) || ($_SESSION['type']!='manager')) && $_SESSION['captcha']['code'] != $_POST['captcha']) {
			?>
	  		<script type="text/javascript">errorMessage("הקוד שהוכנס שגוי");</script>
	    	<?php
	    	//shows the captcha
			//echo "given: " .  $_SESSION['captcha']['code'] . " inserted: " .  $_POST['captcha'];
		}
		//register the user
		else {
			$db = include 'database.php';
			// Create connection
			$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
			mysqli_set_charset($conn,'utf8');
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			
			$stmt = $conn->prepare('SELECT username, id, email FROM Users where username = ? or email = ?');
			$stmt->bind_param('ss', $_POST['username'], $_POST['email']);
			$stmt->execute();

			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				if($row["username"] == $_POST["username"]){
					?>
					<script type="text/javascript">errorMessage("שם המשתמש כבר קיים");</script>
					<?php
					break;
				}
				else{
					if($row["email"] == $_POST["email"]){
				  		?>
				  		<script type="text/javascript">errorMessage("כתובת המייל זו כבר תפוסה");</script>
				    	<?php
				    	break;
					}
				}
			}
			if (empty($row)){
				$stmt = $conn->prepare("INSERT INTO users (email, username, password, firstname, lastname) VALUES (?, ?, ?, ?, ?)");
				$stmt->bind_param("sssss", $email, $username, $password, $firstname, $lastname);

				// set parameters and execute
				$firstname = $_POST["firstname"];
				$lastname = $_POST["lastname"];
				$email = $_POST["email"];
				$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
				$username = $_POST["username"];
				$stmt->execute();
				echo "<user_id hidden>" . $stmt->insert_id . "</user_id>";
				
				$result = $conn->query('insert into Players (user_id) values (' . $stmt->insert_id . ')');
				
				?>
					<script type="text/javascript">alert("המשתמש נוצר בהצלחה");document.location.href="login.php";</script>
				<?php

				//header("location: login.php");
				$stmt->close();
			}
			$conn->close();
		}
		//fill inputs with user's data
		echo "<script type='text/javascript'>document.getElementsByName('firstname')[0].value = '" . $_POST['firstname'] . "';</script>";
		echo "<script type='text/javascript'>document.getElementsByName('lastname')[0].value = '" . $_POST['lastname'] . "';</script>";
		echo "<script type='text/javascript'>document.getElementsByName('username')[0].value = '" . $_POST['username'] . "';</script>";
		echo "<script type='text/javascript'>document.getElementsByName('email')[0].value = '" . $_POST['email'] . "';</script>";
	}
	else {
		//if the user somehow passed the js validation but his input is still bad
		?>
  		<script type="text/javascript">alert("קלט שגוי");</script>
    	<?php
	}
}
$_SESSION['captcha'] = captcha();
echo "<script type='text/javascript'>document.getElementById('captcha_field').src = '" . $_SESSION['captcha']['image_src'] . "';</script>";
?>
