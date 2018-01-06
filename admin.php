<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || ($_SESSION['type'] != 'manager' && $_SESSION['type'] != 'editor' && $_SESSION['type'] != 'secretary')) {
  header("location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles.css"> 
	<title></title>
</head>
<body>
<div style="float:left; position: relative;">
    <button onclick="window.location='logout.php';" style="margin-right: 15px;">יציאה</button>
    <font size="4" color="white">
    ברוכים הבאים,
    <?php 
      echo $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    ?>
    </font>
</div>
<center>
  <br><br><br>
<div class="title">CyberKing</div>
<div class="subtitle">עמוד ניהול ראשי</div>
<br><br><br>
<div class="container">
<button onclick="window.location='userManager.php';" style="width: 100%;">עובדים</button>
<br>
<button onclick="window.location='playerManager.php';" style="width: 100%;">שחקנים</button>
<br>
<button onclick="window.location='questionManager.php';" style="width: 100%;">שאלות</button><br>
<button onclick="window.location='categoryManager.php';" style="width: 100%;">רמות וקטגוריות</button>
</div>
</body>
</html>

