<?php //include 'includes/connection.php'; ?>
<?php //include 'includes/functions.php'; ?>
<?php 
include '../includes/connection.php';
include '../includes/functions.php';
include '../includes/queries.php';
?>

<?php

/*
 * Get total questions
*/
//$query = "SELECT * FROM `questions`";
//
//// Get total results
//$questions = mysqli_query($conn, $query) or die();
//$total = $questions->num_rows;
$total = $results->num_rows;
$next = $total+1;


if(isset($_POST['submit'])) {
	// Get post variables
	$question_number 	= $next;//$_POST['question_number'];
	$question_category  = validateFormData($_POST['question_category']);
	$question_text		= validateFormData($_POST['question_text']);
	
	// Choices array
	$choices = array();
	$choices[1]	= validateFormData($_POST['choice1']); //htmlspecialchars
	$choices[2]	= validateFormData($_POST['choice2']); //htmlentities
	$choices[3]	= validateFormData($_POST['choice3']);
	$choices[4]	= validateFormData($_POST['choice4']);
	$choices[5]	= validateFormData($_POST['choice5']);
	$correct_choice = validateFormData($_POST['correct_choice']);
	
	// Question query
	$query = "INSERT INTO `questions` (question_number, question_category, question)
			  VALUES ('$question_number', '$question_category', '$question_text')";
	
	// Run query
	$insert_row = mysqli_query($conn, $query) or die();

	// Validate insert
	if($insert_row) {
		foreach($choices as $choice => $value) {
			if($value != '') {
				if($correct_choice == $choice) {
					$is_correct = 1;
					$correct_explanation = validateFormData($_POST['correct_explanation']);
					$resources = validateFormData($_POST['resources']);
				} else {
					$is_correct = 0;
					$correct_explanation = null;
					$resources = null;
				}
				
				// Choice query
				$query = "INSERT INTO `choices` (question_number, is_correct, answers, correct_explanation, resources) VALUES ('$question_number', '$is_correct', '$value', '$correct_explanation', '$resources')";
				
				// Run query
				$insert_row = mysqli_query($conn, $query); // or die($mysqli->errno.__LINE__);
				
				// Validate insert
				if($insert_row) {
					continue;
				} else {
					die('Error: (' . $mysqli->errno . ') ' . $mysqli->error);
//					die('Error: (' . $mysqli->errno . ') ' . $mysqli->error);
//					die($mysqli_error . __LINE__);
				}
			}
		}
//		
//		// Choice query
//		$query = "INSERT INTO `choices` (correct_explanation, resources)
//				  VALUES ('$correct_explanation', '$resources')
//				  WHERE is_correct = 1";
//		
//		// Run query
//		$insert_row = mysqli_query($conn, $query); // or die($mysqli->errno.__LINE__);
//
//		// Validate insert
//		if($insert_row) {
//			continue;
//		} else {
//			die('Error: (' . $mysqli->errno . ') ' . $mysqli->error);
////			die($mysqli_error . __LINE__);
//		}
		
		$msg = 'Question has been added';
		$next++;
		
//		header('Location: add.php');
//		exit();
	} else {
		$msg = 'Question was not able to be added';
	}
	
}

if(isset($_POST["cancel"])) {
	header("Location: ../admin/index.php");
}


include("../includes/header.php");
?>

	<main class="addPage">
		<div class="container">
			<h2>Add a Question</h2>
			<?php
				if(isset($msg)) {
					echo "<p class=\"message\">{$msg}</p>";
				}
			?>
			<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
				<p class="hidden">
					<label for="question_number">Question Number: </label>
					<input type="hidden" name="question_number" value="<?= $next; ?>" disabled=true>
				</p>
				<p>
					<label for="question_text">Question Text: </label>
					<input type="text" name="question_text" id="question_text">
				</p>
				<p>
					<label for="question_category">Question Category: </label>
					<input type="text" name="question_category" id="question_category">
				</p>
				<p>
					<label for="choice1">Choice #1: </label>
					<input type="text" name="choice1" id="choice1">
				</p>
				<p>
					<label for="choice2">Choice #2: </label>
					<input type="text" name="choice2" id="choice2">
				</p>
				<p>
					<label for="choice3">Choice #3: </label>
					<input type="text" name="choice3" id="choice3">
				</p>
				<p>
					<label for="choice4">Choice #4: </label>
					<input type="text" name="choice4" id="choice4">
				</p>
				<p>
					<label for="choice5">Choice #5: </label>
					<input type="text" name="choice5" id="choice5">
				</p>
				<p>
					<label for="correct_choice">Correct Choice Number: </label>
					<input type="number" name="correct_choice" id="correct_choice" min="0" max="5">
				</p>
				<p>
					<label for="correct_explanation">Correct Answer Explanation: </label>
					<input type="text" name="correct_explanation" id="correct_explanation">
				</p>
				<p>
					<label for="resources">Question Resources: </label>
					<input type="text" name="resources" id="resources"/><br>
					<small>(Seperate multiple resources with a space.)</small>
				</p>
				<input type="submit" name="cancel" value="Cancel">
				<input type="submit" name="submit" value="submit">
			</form>
			
		</div>
	</main>


<?php include("../includes/footer.php"); ?>