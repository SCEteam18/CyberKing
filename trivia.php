<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
else{
$db = include 'database.php';
// Create connection
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

//check the current status of the player
$result = $conn->query("select p.id, p.startDate, p.question_num from players p join users u on u.id = p.user_id and u.username = '" . $_SESSION['username'] . "'");
if ($result->num_rows > 0) {

    // output data of each row
    while($row = $result->fetch_assoc()) {
        $now = getdate();
        $startDate = date_create_from_format('Y-m-d H:i:s', $row['startDate']);
        $player_id = $row['id'];
        $question_num = $row['question_num'];
        //check if player already played the game
        if ($startDate->format("d") == $now['mday'] && $startDate->format("m") == $now['mon'] && $startDate->format("Y") == $now['year'] && $question_num > $db['questions_num_per_game']) {
			header("location: welcome.php");
	  		exit;
		}
        break;
    }
}

//$result = $conn->query("delete from playerquestions where player_id = " . $player_id);

}
?>
<!DOCTYPE html>
<html lang="he">
	<head>
		<title>Trivia</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<script>
	var score= 0;
	var counter=0;
	var interval=0;
	var currentQuestion = 1;
	var ans = 1;
	var level = 1;
	
	function loadQuestion() {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				if(this.responseText.includes("question")){
					var response = this.responseText.split(";");
					document.getElementById('question').innerHTML = response[1];
					document.getElementById('opt1').textContent = response[2];
					document.getElementById('opt2').textContent = response[3];
					document.getElementById('opt3').textContent = response[4];
					document.getElementById('opt4').textContent = response[5];
					ans = response[6];
					level = response[7];
					document.getElementById('level').textContent = "רמת קושי:" + level;
				}
				else {
					alert("שגיאה בעמוד. נא לנסות מאוחר יותר\n" + this.responseText);
					document.location.href = "welcome.php";
				}
            }
        };
        xmlhttp.open("GET", "getQuestion.php", true);
        xmlhttp.send();
	}
	
	function NextQuestion(){
			var opt1 = document.getElementById('opt1');
			var opt2 = document.getElementById('opt2');
			var opt3 = document.getElementById('opt3');
			var opt4 = document.getElementById('opt4');
			var result = document.getElementById('result');
			var next= document.getElementById('nextButton');
			var questionE = document.getElementById('question');
			var container = document.getElementById('triviaContainer');
            var selected=document.querySelector('input[type=radio]:checked');

            if(!selected){
	            alert('אנא בחר תשובה!');
	            return;
            }
            clearInterval(interval);
            var penalty = Math.floor(counter/60) * 10;
            if (penalty > 100)
            	penalty = 100;
            var new_score = 0;

            if(selected.value == ans){
	        	new_score = (100 + level*10) - penalty;
            	score += new_score;
            }
            selected.checked = false;
            counter=0;
			setTimer();
            scor.textContent = score;
            currentQuestion++;

            if(currentQuestion == NumberOfQuestions - 1){
         	     nextButton.textContent = 'Finish';
				 nextButton.name='act';
            }
            if(currentQuestion == NumberOfQuestions){
				window.location.href = "end.php?score=" + score;
	            container.style.display = 'none';
	            resultCont.style.display = '';
	            resultCont.textContent = 'Your Score: ' + score;
            }
			document.getElementById("questionNumber").innerHTML = "שאלה " + currentQuestion;
            loadQuestion();
        }
		
	</script>
	<body dir="rtl" onload="javascript:loadQuestion();">
	<center>
    <div class="title">טריוויה</div>
    <div id="questionNumber" class="subtitle">שאלה 1</div>
	<div id="level" class="subtitle">רמת קושי: 1</div>
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
		
	<div id="result" class="container result" style="display:none;">
	</div>
	
	<script>
	var tim=document.getElementById('time');
	var scor=document.getElementById('score');
	scor.textContent=score;
    tim.textContent="00:00:00";
	var NumberOfQuestions = 30;

	setTimer();

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
			if(counter>120){
				tim.style.color='#ff3300';
			}
			if(counter>60&&counter<120){
				tim.style.color='#0066ff';
			}
			}, 1000);
	}
    </script>
	
	</body>
</html>