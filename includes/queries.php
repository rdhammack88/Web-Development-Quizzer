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
$results = mysqli_query($conn, $query) or die();

/*
 * Choice Page On Load
*/
$query		= "SELECT `question_category`
			   FROM `questions`
			   GROUP BY `question_category`
			   HAVING COUNT(*) >= 2";
$categories = mysqli_query($conn, $query);

if(isset($_SESSION['category'])){
/*
 * Final Page query results
*/
$query = "SELECT questions.question_number, questions.question, questions.question_category, choices.id, choices.is_correct, choices.answers, choices.correct_explanation, choices.resources 
FROM questions 
LEFT JOIN choices ON questions.question_number = choices.question_number 
WHERE questions.question_category = '" . $_SESSION['category'] . "' AND questions.question_number IN (" . implode(", ", $_SESSION["used_questions"]) .")
ORDER BY field(questions.question_number, " . implode(", ", $_SESSION["used_questions"]) ."), field(id, " . implode(", ", $_SESSION["answer_order"]) .")";
$finale_queries = mysqli_query($conn, $query);
}
?>