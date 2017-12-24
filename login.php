<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
  <script type="text/javascript">
    function wrongPassword(){
      document.getElementById('error_msg').innerText = 'שם המשתמש או הסיסמא אינם נכונים';
      return;
    }
	
	function inactiveUser(){
      document.getElementById('error_msg').innerText = 'המשתמש שלך אינו פעיל אנא צור קשר עם מנהל האתר';
      return;
    }
  </script>
	<link rel="stylesheet" type="text/css" href="styles.css"> 
	<title></title>
</head>
<body>
<center>
<div class="title">CyberKing</div>
<div class="subtitle">חידון הסייבר הארצי</div>
<br><br><br>
<div class="container">
  <form action="login.php" method="post">
    <input type="text" name="username" style="text-align: center;" placeholder="שם משתמש"/>
    <input type="password" name="password" style="text-align: center;" placeholder="סיסמה"/>
    <button style="width: 100%;">כניסה</button>
    <p class="message">לא רשום? <a href="register.php">צור משתמש</a></p>
  </form>
  <br>
  <div id="error_msg" style="color: red;"></div>
</div>
</body>
</html>

<?php
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
  $db = include 'database.php';

  // Create connection
  $conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
  mysqli_set_charset($conn,'utf8');
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  
  $sql = "SELECT firstname, lastname, password, type,enabled FROM Users WHERE username = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $_POST['username']);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($firstname, $lastname, $password, $type,$enabled);
  $stmt->fetch();
  $stmt->close();
  
  if (empty($password)){
    ?>
    <script type="text/javascript">wrongPassword();</script>
    <?php
  }
  else{
    if (password_verify($_POST['password'], $password)){
	  if($enabled==1){
      session_start();
      $_SESSION['firstname'] = $firstname;
      $_SESSION['lastname'] = $lastname;
      $_SESSION['max_score'] = 0;
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['type'] = $type;
      if ($_SESSION['type'] == 'player') {
        header("Location: welcome.php");
      }
      else {
        header("Location: admin.php");
      }
	  }
	  else{
		  ?>
      <script type="text/javascript">inactiveUser();</script>
      <?php
		  
	  }
    }
    else{
      ?>
      <script type="text/javascript">wrongPassword();</script>
      <?php
    }
  }

  $conn->close();
}
?>

