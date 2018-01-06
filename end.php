<!doctype html>

<?php
  session_start();
if (isset($_SESSION['player_id']) && !empty($_SESSION['player_id'])) {
    header('Content-Type: text/html; charset=utf-8');
  

	$db = include 'database.php';
	// Create connection
	$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
	mysqli_set_charset($conn,'utf8');
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$player_id = $_SESSION['player_id'];

	// update category records with new score, if it's bigger
	$sql = "UPDATE categoryrecords cr
			JOIN (SELECT q.category_id, sum(pq.score) AS score
								FROM playerquestions pq
								JOIN questions q ON q.id = pq.question_id
								WHERE pq.player_id = $player_id
								GROUP BY q.category_id
				  ) AS cs 
			      ON cs.category_id = cr.category_id 
			SET cr.max_score = cs.score
			WHERE cr.player_id = $player_id AND cs.score > cr.max_score";
	$conn->query($sql);

	// add a category record if its missing
	$sql = "INSERT INTO categoryrecords (player_id, category_id, max_score)
			SELECT pq.player_id, q.category_id, sum(pq.score)
			FROM playerquestions pq 
			JOIN questions q ON q.id = pq.question_id
			WHERE q.category_id not IN (
			    SELECT cr.category_id 
			    FROM categoryrecords cr
			    WHERE cr.player_id = $player_id)
			GROUP BY q.category_id
			";
	$conn->query($sql);
    $conn->close();
}
?>

<html lang="he">
<head>
	<link rel="stylesheet" type="text/css" href="styles.css">
<title> טריוויה </title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body align="middle" dir="rtl">
<font color="white">
	<br></br><br></br><br></br>
	<h4>סיימת את הטרויוויה!</h4>
	<h4>הניקוד שלך: <?php echo $_SESSION['score']; ?></h4>
</font>
<button onclick="document.location.href = 'welcome.php'">סיים</button>
</body>
</html>