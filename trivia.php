<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
date_default_timezone_set('Asia/Jerusalem');
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])) {
  header("location: login.php");
  exit;
}
else {
$db = include 'database.php';
// Create connection
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

	$sql = "SELECT p.score, p.question_num, p.startDate
			FROM players p
			WHERE p.id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $player_id);
	$player_id = $_SESSION['player_id'];
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($_SESSION['score'], $_SESSION['question_num'], $startDate);
	$stmt->fetch();
	$_SESSION['startDate'] = $startDate;
	$str_startDate = $startDate;
	$startDate = date_create_from_format('Y-m-d H:i:s', $startDate);
	$str_now = date('Y-m-d H:i:s');
	$now = date_create_from_format('Y-m-d H:i:s', $str_now);
	
	$played_today = !(strtotime($str_startDate) < strtotime("-1 day"));
	if ($played_today) {
		$time_passed = strtotime($str_now) - strtotime($str_startDate);
	}
	else {
		$time_passed = 0;
	}

	if ($played_today && $_SESSION['question_num'] > $db['questions_num_per_game']) {
		header("location: welcome.php");
  		exit;
	}
	else if (!$played_today || $_SESSION['question_num'] == "0") {
	// didnt play today or never played at all? player can start a new game
		$stmt = $conn->prepare("UPDATE players SET question_num = 1, startDate = now(), score = 0 WHERE id = ?");
		$stmt->bind_param("s", $player_id);
		$player_id = $_SESSION['player_id'];
		$stmt->execute();
		$stmt = $conn->prepare("DELETE FROM playerquestions WHERE player_id = ?");
		$stmt->bind_param("s", $player_id);
		$player_id = $_SESSION['player_id'];
		$stmt->execute();
		$_SESSION['score'] = 0;
		$_SESSION['question_num'] = 1;

		// select a random level 1 question
		$sql = "SELECT q.*, c.name as category_name
				FROM questions AS q
				JOIN categories c on c.id = q.category_id 
				WHERE q.level_id = 1
				ORDER BY RAND()
				LIMIT 1";
	}
	else {
		// the player rejoined the game, continue with same question
		$sql = "SELECT q.*, c.name as category_name
				FROM questions q
				JOIN categories c on c.id = q.category_id 
				WHERE q.id = 
				 	(SELECT question_id 
				 	FROM playerquestions pq
				 	WHERE pq.player_id = " . $player_id . "
				 	ORDER BY pq.id DESC
				 	LIMIT 1)";
	}

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$question_id = $row["id"];
		$level_id = $row["level_id"];
		$category_name = $row["category_name"];
		$title = $row["title"];
		$answer1 = $row["answer1"];
		$answer2 = $row["answer2"];
		$answer3 = $row["answer3"];
		$answer4 = $row["answer4"];

	    // add the question to player's history
		$sql = "INSERT INTO playerquestions (player_id, question_id)
				SELECT * FROM (SELECT $player_id as pid, $question_id as qid) AS tmp
				WHERE NOT EXISTS (
				    SELECT player_id, question_id 
				    FROM playerquestions 
				    WHERE player_id = $player_id and question_id = $question_id
				) LIMIT 1;";
		$conn->query($sql);
	}
	else {
		echo "אין יותר שאלות במאגר";
	}

	$stmt->close();
	$conn->close();
} // end of else of !isset($_SESSION['username'])
?>
<!DOCTYPE html>
<html lang="he">
	<head>
		<title>Trivia</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<script>
	var score = <?php echo $_SESSION['score']; ?>;
	var counter = <?php echo $time_passed; ?>;
	var interval = 0;
	var currentQuestion = <?php echo $_SESSION['question_num']; ?>;
	var level = <?php echo $level_id; ?>;
	var category_name = <?php echo "'$category_name'"; ?>;
	var title = <?php echo "'$title'"; ?>;
	var ans1 = <?php echo "'$answer1'"; ?>;
	var ans2 = <?php echo "'$answer2'"; ?>;
	var ans3 = <?php echo "'$answer3'"; ?>;
	var ans4 = <?php echo "'$answer4'"; ?>;
	
	function NextQuestion() {
        var selected=document.querySelector('input[type=radio]:checked');
		if(!selected) {
            alert('אנא בחר תשובה!');
            return;
        }

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				if(this.responseText.includes("תשובה")) {
					
					var response = this.responseText.split(";");

					alert(this.responseText);
					if (response[1] == "1") {
						document.location.href = "end.php";
					}
					else {
						location.reload();
					}
				}
				else {
					alert("שגיאה בעמוד. נא לנסות מאוחר יותר\n" + this.responseText);
					//document.location.href = "welcome.php";
				}
            }
        };

        xmlhttp.open("GET", "getQuestion.php?ans=" + selected.value, true);
        xmlhttp.send();
    }
		
	</script>
	<body dir="rtl">
	<div style="float:left; position: relative;">
		<font size="4" color="white">
		ברוכים הבאים,
		<?php 
			echo $_SESSION['firstname'] . " " . $_SESSION['lastname'];
		?>
		</font>
		<button onclick="window.location='welcome.php';" style="margin-right: 15px;">תפריט ראשי</button>
		<button onclick="window.location='logout.php';">יציאה</button>
	</div>
	<br><br><br>
	<center>
    <div class="title">טריוויה</div>
    <br>
    <div style="width: 600px;">
		 <div class="subtitle" style="float: left; width: 200px;"><?php echo "קטגוריה: " . $category_name; ?></div>
		 <div class="subtitle" style="float: left; width: 200px;"><?php echo "רמת קושי: " . $level_id; ?></div>
		 <div class="subtitle" style="float: left; width: 200px;"><?php echo "שאלה: " . $_SESSION['question_num']; ?></div>
		 <br style="clear: left;" />
	</div>
    <br>
	<div class="info">
	    <div class="score"><h2>ניקוד</h2><h3 id="score"></h3></div>
		<div class="time" id="t"><h2>זמן</h2><h3 id="time"></h3></div>
	</div>
	<br><br><br><br>
	<div id="triviaContainer" class="container" style="width: 700px;">
	    <div id="question" class="question"></div>
		<br>
				
        <input type="radio" id="radio1" name="option" value="1">
        <label for="radio1" class="option"><span id="opt1"></span></label>

        <input type="radio" id="radio2" name="option" value="2">
        <label for="radio2" class="option"><span id="opt2"></span></label>
        <br>

        <input type="radio" id="radio3" name="option" value="3">
        <label for="radio3" class="option"><span id="opt3"></span></label>
				
     	<input type="radio" id="radio4" name="option" value="4">
        <label for="radio4" class="option"><span id="opt4"></span></label>
		<br><br><br>

		<button type="submit" name="" id="nextButton" onclick="NextQuestion()" >שאלה הבאה</button>
		
    </div>
	
	<script>
	var tim=document.getElementById('time');
	var scor=document.getElementById('score');
	scor.textContent = score;
    tim.textContent="00:00:00";
	var NumberOfQuestions = <?php echo $db['questions_num_per_game']; ?>;

	setTimer();

	//fixes time format by adding a zero
	function pad(val) {
		var valString = val + "";
		if (valString.length < 2) {
			return "0" + valString;
		}
		return valString;
	}

	function setTimer() {
		interval = setInterval(function () {
			++counter;
			var hour = Math.floor(counter /3600);
   			var minute = Math.floor((counter - hour*3600)/60);
   			var seconds = counter - (hour*3600 + minute*60);
			tim.textContent = pad(hour) + ":" + pad(minute) + ":" + pad(seconds);
			if(counter<60){
				tim.style.color='#00b300';
			}
			if(counter>60&&counter<240){
				tim.style.color='#0066ff';
			}
			if(counter>240){
				tim.style.color='#ff3300';
			}
			}, 1000);
	}

	document.getElementById('question').innerHTML = title;
	document.getElementById('opt1').textContent = ans1;
	document.getElementById('opt2').textContent = ans2;
	document.getElementById('opt3').textContent = ans3;
	document.getElementById('opt4').textContent = ans4;
    </script>
	</body>
</html>