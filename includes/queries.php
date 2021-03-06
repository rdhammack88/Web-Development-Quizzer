<?php require_once("connection.php"); ?>
<?php

if(isset($_GET['category'])) {
	session_start();
	$category = $_GET['category'];
	$query = "SELECT * 
			  FROM `questions`
			  WHERE `question_category` = '$category'";
	$results = mysqli_query($conn, $query);	
	$question_count = mysqli_num_rows($results);
	$_SESSION['question_count'] = $question_count;
	echo $question_count;
}

/*
 * Get total questions
*/
$query 	= "SELECT * FROM `questions`";
$results = mysqli_query($conn, $query);// or die();

//$query = "SELECT COUNT(*)
//		  FROM `questions`";
//$question_count = mysqli_query($conn, $query);

/*
 * Choice Page On Load
*/
$query		= "SELECT `question_category`
			   FROM `questions`
			   GROUP BY `question_category`
			   HAVING COUNT(*) >= 2";
$categories = mysqli_query($conn, $query);

/*
 * Final Page query results
*/
//if(isset($_SESSION['category'])){
if(isset($_SESSION['finished'])){

	$category = $_SESSION['category'];
	$used_questions = implode(', ', $_SESSION['used_questions']);
	$answer_order = implode(', ', $_SESSION['answer_order']);
//	echo $category;
//	echo '<br>';
//	echo $used_questions;
//	echo '<br>';
//	echo $answer_order;
//	echo '<br>';
//	exit();
	
	$query = "SELECT questions.question_number, questions.question, questions.question_category, choices.id, choices.is_correct, choices.answers, choices.correct_explanation, choices.resources 
	FROM questions 
	LEFT JOIN choices ON questions.question_number = choices.question_number 
	WHERE questions.question_category = '$category' AND questions.question_number IN ($used_questions)
	ORDER BY field(questions.question_number, $used_questions), field(id, $answer_order)";	

//	$query = "SELECT questions.question_number, questions.question, questions.question_category, choices.id, choices.is_correct, choices.answers, choices.correct_explanation, choices.resources 
//	FROM questions 
//	LEFT JOIN choices ON questions.question_number = choices.question_number 
//	WHERE questions.question_category = '" . $_SESSION['category'] . "' AND questions.question_number IN (" . implode(", ", $_SESSION["used_questions"]) .")
//	ORDER BY field(questions.question_number, " . implode(", ", $_SESSION["used_questions"]) ."), field(id, " . implode(", ", $_SESSION["answer_order"]) .")";
	$finale_queries = mysqli_query($conn, $query);
}


//if(isset($_SESSION['start']) && isset($_SESSION['end'])) {
//	$start 	= $_SESSION['start'];
//	$end 	= $_SESSION['end'];
//} else {
//	$start 	= 0;
//	$end	= 10;
//}
//$query 		= "SELECT * 
//			   FROM `questions`
//			   WHERE question_number <= $end 
//			   AND question_number > $start";
////			   LIMIT 10";
//$questions 	= mysqli_query($conn, $query);
?>