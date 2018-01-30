<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/queries.php"); ?>
<?php
session_start();
// Check to see if score is set
if(!isset($_SESSION['score'])) {
	$_SESSION['score'] = 0;
}
// Check if answer was submitted
if(isset($_POST['submit'])) {
	$number = $_POST['number'];
	$question_number = $_POST['questionNumber'];
	
	if(!isset($_POST['choice']) || $_POST['choice'] == '') {
//		$referredPage = $_SERVER['HTTP_REFERER'];
//		header("Location: $referredPage");
//		echo "Please choose an answer";
//		echo $question_number;
//		exit();
		header("Location: questions.php?qn={$question_number}&n={$number}");
	} else {
		$selected_choice = $_POST['choice'];
		$next = $number + 1;
		array_push($_SESSION['user_answers'], $selected_choice);
		/*
		 * Get total questions
		*/
		$total = $_SESSION['total_questions'];
		/*
		 * Get Correct Choice
		*/
		$query = "SELECT * FROM `choices`
				  WHERE question_number = $question_number AND is_correct = 1";
		// Get result
		$correct = mysqli_fetch_assoc(mysqli_query($conn, $query)) or die();
		// Set correct choice
		$correct_choice = $correct['id'];
		// Check if user is on last question
		if($number >= $total) {
			if($correct_choice == $selected_choice) {
				array_push($_SESSION["right_wrong"], "correct");
			} else {
				array_push($_SESSION["right_wrong"], "wrong");
			}
			++$_SESSION['score'];
			header("Location: final.php");
			exit();
		}
		// Compare user answer with correct answer
		if($correct_choice == $selected_choice) {
			// Answer is correct
			++$_SESSION['score'];
			array_push($_SESSION["right_wrong"], "correct");
			header("Location: questions.php?n={$next}");
			exit();
		} else {
			array_push($_SESSION["right_wrong"], "wrong");
			header("Location: questions.php?n={$next}");
			exit();
		}
	}	
}

if(isset($_POST["quit"])) {
	mysqli_close();
	$_SESSION["score"] = 0;
	$_SESSION["total_questions"] = 0;
	$_SESSION["category"] = "";
	$_SESSION["used_questions"] = [];
	$_SESSION["user_answers"] = [];
	$_SESSION["right_wrong"] = [];
	$_SESSION['answer_order'] = [];
	header("Location: index.php");
	exit();
}

?>
