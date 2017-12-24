<?php
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


//$random_question = rand(0,$result->num_rows);
//$question_number = 0;
$result = $conn->query("SELECT *
  FROM questions AS r1 JOIN
       (SELECT CEIL(RAND() *
                     (SELECT MAX(id)
                        FROM questions)) AS id)
        AS r2
 WHERE r1.id >= r2.id and r1.id not in (select question_id from playerquestions where player_id = " . $player_id . ")
 ORDER BY r1.id ASC
 LIMIT 1");

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       //if ($question_number++ == $random_question) {
			echo "question;" . $row['title'] . ";" . $row['answer1']. ";" . $row['answer2']. ";" . $row['answer3']. ";" . $row['answer4']. ";" . $row['right_answer']. ";" . $row['level_id'];
			$result = $conn->query('insert into playerquestions (player_id, question_id) values (' . $row['id'] . ', ' . $row['id'] . ')');
			break;
		//}
    }
}
else {
	echo "אין יותר שאלות";
}

$conn->close();
	
?>