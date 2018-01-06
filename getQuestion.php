<?php
//if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET) && strpos($_SERVER['HTTP_REFERER'], 'trivia.php') !== false) {
if (true) {
session_start();
date_default_timezone_set('Asia/Jerusalem');
$db = include 'database.php';

// Create connection
$conn = new mysqli($db['servername'], $db['username'], $db['password'], $db['dbname']);
mysqli_set_charset($conn,'utf8');
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$player_id = $_SESSION['player_id'];

// select player's last question
$sql = "SELECT q.level_id, q.right_answer, ql.score AS level_score, pq.id AS last_question,
          (SELECT count(*) id 
            FROM questionlevels) AS total_levels_count,
          (SELECT count(*) id 
            FROM categories) AS total_category_count,
          (SELECT count(*) 
            FROM playerquestions pq3
            JOIN questions q2 ON q2.id = pq3.question_id
            WHERE pq3.player_id = $player_id AND q2.level_id = q.level_id) AS player_level_count,
          (SELECT startDate
            FROM players
            WHERE id = $player_id) AS startDate
        FROM playerquestions pq
        JOIN questions q ON q.id = pq.question_id
        JOIN questionlevels ql ON ql.id = q.level_id
        WHERE pq.id = (SELECT max(pq2.id)
                      FROM playerquestions pq2
                      WHERE pq2.player_id = $player_id)";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$startDate = $row['startDate'];
$_SESSION['startDate'] = $startDate;
$level_score = $row['level_score'];
$total_category_count = $row['total_category_count'];
$total_levels_count = $row['total_levels_count'];
$player_level_count = $row['player_level_count'];
$questions_per_level = floor($db['questions_num_per_game'] / $total_levels_count);
$last_question = $row['last_question'];
$level_id = $row['level_id'];
$right_answer = $row['right_answer'];
$score = $_SESSION['score'];
$end = 0;

//check if last question
if ($_SESSION['question_num'] == $db['questions_num_per_game']) {
  $end = 1;
}

//if the user answered right
if ($right_answer == $_GET['ans']) {

  $time_passed = strtotime(date('Y-m-d H:i:s')) - strtotime($_SESSION['startDate']);

  //calculate score and update table
  $question_score = 0;
  $penalty = floor($time_passed / 60) * 10;
  $bonus = 0;

  if ($level_id > 1) {
    $bonus = $level_id * 10;
  }

  if ($penalty > 100) {
    $penalty = 100;
  }

  //get the score of this level
  
  $question_score = $level_score + $bonus - $penalty;
  $score += $question_score;
  
  $sql = "UPDATE playerquestions
          SET score = $question_score
          WHERE id = $last_question ;";
  $conn->query($sql);



  $sql = "UPDATE players
          SET score = $score, startDate = now(), question_num = " . ($_SESSION['question_num'] + 1) . 
          " WHERE id = $player_id ;";
  $conn->query($sql);
  echo "תשובה נכונה;";
}
else {
  $sql = "UPDATE players
          SET startDate = now(), question_num = " . ($_SESSION['question_num'] + 1) . 
          " WHERE id = $player_id ;";
  $conn->query($sql);
  echo "תשובה לא נכונה;";
}

// check if the player had enough questions of current level. if so, progress to next level
if ($player_level_count >= $questions_per_level) {
  $level_id++;
  // the game is not over but he is in the last level and had enough questions for this level
  // give more questions of the last level
  if ($level_id > $total_levels_count) {
    $level_id = $total_levels_count;
  }
}

// find the least picked category
// get the category that has the least answers for this level

$sql = "SELECT *
        FROM (SELECT c.id as category_id,
                (SELECT count(*) 
                 FROM questions q
                 JOIN playerquestions pq ON pq.question_id = q.id 
                  AND pq.player_id = $player_id AND q.level_id = $level_id
                 WHERE q.category_id = c.id
                )AS question_count
               FROM categories c
              ) AS cats
        WHERE cats.question_count = 
          (SELECT min(question_count)
          FROM (SELECT c.id,
                    (SELECT count(*) 
                    FROM questions q
                    JOIN playerquestions pq ON pq.question_id = q.id 
                      AND pq.player_id = $player_id AND q.level_id = $level_id
                    WHERE q.category_id = c.id
                    )AS question_count
                FROM categories c
                ) AS cat_count
           )
        ORDER BY RAND();";
$result = $conn->query($sql);

$categories = array();
$index = 0;
// we need to store the categories inside an array because the lowest category id isn't alway the least picked one
while($row = $result->fetch_assoc()) {
  $categories[$index++] = $row['category_id'];
}

$found_question = 0;
// check if current level has any questions to offer, if not loop to next level
while ($level_id <= $total_levels_count) {

  // loop through categories, starting from the least picked one

  for ($i = 0; $i < sizeof($categories); $i++) {
    // get an unanswered question in the least picked category, in current level
    $sql = "SELECT q.id
            FROM questions q
            JOIN categories c on q.category_id = c.id
            AND q.level_id = $level_id
            AND q.id NOT IN (
                            SELECT pq.question_id
                            FROM playerquestions pq
                            WHERE pq.player_id = $player_id
                            )
            AND c.id = " . $categories[$i] . "
            ORDER BY RAND()
            LIMIT 1;";
    $result = $conn->query($sql);

    //insert the question into player's history
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $question_id = $row['id'];
      $sql = "INSERT INTO playerquestions (player_id, question_id)
              SELECT * FROM (SELECT $player_id as pid, $question_id as qid) AS tmp
              WHERE NOT EXISTS (
                  SELECT player_id, question_id 
                  FROM playerquestions 
                  WHERE player_id = $player_id AND question_id = $question_id
              ) LIMIT 1;";
      $conn->query($sql);
      $found_question = 1;
      echo "\n level: $level_id \n";
      echo " category: " . $categories[$i] . "\n";
      break;
    }
  }

  // if there are no questions to give for any category in this level, progress the level and repeat loop
  if ($found_question == 1) {
    break;
  }

  $level_id++;
} // end of while ($level_id ...)

// still didn't find a question. pick a random unanswered question
if ($found_question == 0) {
  $level_id = 10;
  $_SESSION['question_num'] = 31;
  $sql = "SELECT q.id
            FROM questions q
            WHERE q.level_id = $level_id
            AND q.id NOT IN (
                            SELECT pq.question_id
                            FROM playerquestions pq
                            WHERE pq.player_id = $player_id
                            )
            ORDER BY RAND()
            LIMIT 1;";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $question_id = $row['id'];
      $sql = "INSERT INTO playerquestions (player_id, question_id)
                SELECT * FROM (SELECT $player_id as pid, $question_id as qid) AS tmp
                WHERE NOT EXISTS (
                    SELECT player_id, question_id 
                    FROM playerquestions 
                    WHERE player_id = $player_id AND question_id = $question_id
                ) LIMIT 1;";
        $conn->query($sql);
    }
    else {
      // no more questions. finish game
      $_SESSION['end'] = 1;
      $end = 1;
    }
}

echo $end;

$conn->close();
} // end of if $_SERVER['REQUEST_METHOD'] == 'GET'
?>