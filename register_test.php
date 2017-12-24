<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || ($_SESSION['type'] != 'manager' )){
  header("location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
	$db = include 'database.php';
	$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM Users where id = " . $_POST['id'];
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
	    echo "<br>" . var_dump($row) .  "<br>";
	}
	} else {
	echo "0 results";
	}
	$conn->close();
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
	<script src="jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		var user_id = "0";

		function delete_user(){
			$.get("deleteRow.php?table=users&id=" + user_id, function(data, status){
		        $("#output").html($("#output").html() + data);
		    });
		}

		function show_user(){
			$.post("register_test.php",
		    {
		        id: user_id
		    },
		    function(data, status){
		    	$("#output").html($("#output").html() + "<br><br>" + data.split('[').join('<br>['));
		    	delete_user();
			});
		}

		function perform_test(){
			var firstname = $("#firstname").val();
			var lastname = $("#lastname").val();
			var username = $("#username").val();
			var password = $("#password").val();
			var email = $("#email").val();
			var captcha = '12345';

		    $.post("register.php",
		    {
		        firstname: firstname,
		        lastname: lastname,
		        username: username,
		        password: password,
		        email: email,
		        captcha: captcha
		    },
		    function(data, status){
		    	var userExists = "שם המשתמש כבר קיים";
		    	var emailExists = "כתובת המייל זו כבר תפוסה";
		    	var badEmail = "קלט שגוי";
				$("#user_id").text("");
				$("#error").hide();

		    	if (data.includes(userExists)){
		    		$("#output").text(userExists);
		    	}

		    	if (data.includes(emailExists)){
		    		$("#output").text(emailExists);
		    	}

		    	if (data.includes(badEmail)){
		    		$("#output").text(badEmail);
		    	}

		    	if (data.includes("user_id")){
		    		$("#output").html("המשתמש נוצר בהצלחה");
		    		var element = "<user_id hidden>";
		    		user_id = data.substring(data.lastIndexOf(element)+element.length, data.lastIndexOf("</user_id>"));
		    		show_user();
		    	}
		    	else {		
		    		$("#error").show();
		    		$("#output").html(data);
		    	}
		    });

		}
	</script>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>Registration test</title>
</head>
<body>
	<br><br><center>
<div class="subtitle">
User registration
</div>
<br>
<input id="firstname" placeholder="שם פרטי" value="test_firstname"/>
<input id="lastname" placeholder="שם משפחה" value="test_lastname"/>
<input id="username" placeholder="שם משתמש" value="test_username"/>
<input id="password" placeholder="סיסמה" value="test_password"/>
<input id="email" placeholder="כתובת מייל" value="test_email@contoso.com"/>
<br><br>
<button onclick="javascript:perform_test();">perform test</button>
<br>
<p id="error" style="color:red;" hidden>:שגיאה בסקריפט</p>
<br>
<p id="output"></p>
<br>
<p id="user_id"></p>
</body>
</html>