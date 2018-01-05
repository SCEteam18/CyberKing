<!doctype html>

<?php

    header('Content-Type: text/html; charset=utf-8');
    session_start();
	$db = include 'database.php';
	// Create connection
	$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
	mysqli_set_charset($conn,'utf8');
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$result = $conn->query("select p.id from players p join users u on u.id = p.user_id and u.username = '" . $_SESSION['username'] . "'");
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
		   $player_id = $row['id'];
			break; 
		}
	}
	
	$score=$_GET['score'];
    $query="insert into categoryrecords (player_id,category_id, max_score) values (" . $player_id . ", 1, " . $score . ")";
    mysqli_query($conn,$query);
	
	$query="update players set score = " . $score . " where id = " . $player_id;
    mysqli_query($conn,$query);

?>

<html lang="he">

  <head>
  
    <title> טריוויה </title>
	<link rel="stylesheet" type="text/css" href="style.css">
  </head>
  
  <body align="middle" dir="rtl">
  
    <br></br><br></br><br></br>
	
    <h4>סיימת את הטרויוויה!</h4>
	
    	
	<div class="end" ><a href="welcome.php">סיים</a></div>
	
	
	
  </body>
</html>