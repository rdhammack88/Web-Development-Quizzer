<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<?php
session_start();
//echo $_GET['question_number'];
if(isset($_GET['question_number'])) {
	$_SESSION['question_number'] = $_GET['question_number'];
//	$_SESSION['category'] = $_GET['category'];
}

$answers = [];
$query = "SELECT questions.question_number, questions.question, questions.question_category, choices.id, choices.is_correct, choices.answers, choices.correct_reason, choices.resources
FROM questions 
LEFT JOIN choices ON questions.question_number = choices.question_number 
WHERE questions.question_number =" . $_SESSION['question_number'];
$edit_question = mysqli_query($conn, $query);
//$row_count = mysqli_num_rows($edit_question);
//echo $row_count;

while($row = mysqli_fetch_assoc($edit_question)) {
	$question = $row['question'];
	$question_number = $row['question_number'];
	$category = $row['question_category'];
//	array_push($answers, $row['answers']);
	$answers[$row['id']] = $row['answers'];
	
	if($row['is_correct']) {
		//$correct_answer = $row['id'];
		$correct = $row['answers'];
		$correct_reason = $row['correct_reason'];
		$resources = $row['resources'];
	}
}
print_r($answers);


/*
 * Get total questions
*/
//$query = "SELECT * FROM `questions`";
//
//// Get total results
//$questions = mysqli_query($conn, $query) or die();
//$total = $questions->num_rows;
//$next = $total+1;


if(isset($_POST['submit'])) {
	// Get post variables
	$question_number 	= $_POST['question_number'];
	$question_category  = validateFormData($_POST['question_category']);
	$question_text		= validateFormData($_POST['question_text']);
	$correct_reason = validateFormData($_POST['correct_reason']);
	$resources = validateFormData($_POST['resources']);
	
	// Choices array
	$choices = array();
	$choices[1]	= validateFormData($_POST['choice1']); //htmlspecialchars
	$choices[2]	= validateFormData($_POST['choice2']); //htmlentities
	$choices[3]	= validateFormData($_POST['choice3']);
	$choices[4]	= validateFormData($_POST['choice4']);
	$choices[5]	= validateFormData($_POST['choice5']);
	$correct_choice = validateFormData($_POST['correct_choice']);
	
	// Question query
//	$query = "INSERT INTO `questions` (question_number, question_category, question)
//			  VALUES ('$question_number', '$question_category', '$question_text')";
	
//	$query = "UPDATE `questions`, `choices`
//			  SET  questions.question = $question_text,
//			  questions.question_category = $question_category, 
//			  choices.is_correct = $correct_choice, 
//			  choices.correct_reason = $correct_reason, 
//			  choices.resources = $resources
//			  WHERE question_number = $question_number";
	
	$query = "UPDATE `questions`
			  SET  questions.question = $question_text,
			  questions.question_category = $question_category
			  WHERE question_number = $question_number";
	
	// questions.question_number = $question_number, choices.answers = ,  choices.question_number = $question_number, 
	
	
	
	
	
	// Run query
	$insert_row = mysqli_query($conn, $query) or die();

	// Validate insert
	if($insert_row) {
		foreach($choices as $choice => $value) {
			if($value != '') {
				if($correct_choice == $choice) {
					$is_correct = 1;
				} else {
					$is_correct = 0;
				}
				
				// Choice query
//				$query = "INSERT INTO `choices` (question_number, is_correct, answers) VALUES ('$question_number', '$is_correct', '$value')";
				
				$query = "UPDATE `choices`
						  SET choices.is_correct = $correct_choice, 
						  choices.correct_reason = $correct_reason, 
						  choices.resources = $resources
						  WHERE question_number = $question_number
						  AND answers = $choices[$correct_choice]";
						  //AND id = ";
				
				
				// Run query
				$insert_row = mysqli_query($conn, $query) or die($mysqli->errno.__LINE__);
				
				// Validate insert
				if($insert_row) {
					continue;
				} else {
					die('Error: (' . $mysqli->errno . ') ' . $mysqli->error);
				}
			}
		}
		
		$msg = 'Question has been added';
		$next++;
		
//		header('Location: add.php');
//		exit();
	} else {
		$msg = 'Question was not able to be added';
	}
	
}

if(isset($_POST["cancel"])) {
	$start 	= 0;
	$end	= 10;
	$_SESSION['start'] 	= $start;
	$_SESSION['end']	= $end;
	//$_SESSION['total_count'] = mysqli_num_rows($results);
	header("Location: admin.php");
	exit();
}



?>

<?php include("includes/header.php"); ?>


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
					<input type="hidden" name="question_number" value="<?= $question_number; ?>" placeholder="<?php echo $question_number;?>" disabled=true>
				</p>
				<p>
					<label for="question_text">Question Text: </label>
					<input type="text" name="question_text" id="question_text" placeholder="<?php echo $question; ?>">
				</p>
				<p>
					<label for="question_category">Question Category: </label>
					<input type="text" name="question_category" id="question_category" placeholder="<?php echo $category; ?>">
				</p>
				
				<?php $i = 1; foreach($answers as $key => $answer) : ?>
				<p><?php //echo $key ?></p>
				<p>
					<label for="choice<?= $i ?>">Choice #<?= $i; ?>: </label>
					<input type="text" name="choice<?= $i; ?>" id="choice<?php $i; ?>" placeholder="<?= $answer; ?>">
					<input type="hidden" name="" value="<?= $key ?>">
				</p>
				<?php if($answer == $correct) : ?>
				<?php $correct_answer = $i; ?>
				<?php endif; $i++; ?>
				<?php endforeach; ?>
				
				
				<?php  ?>
				<?php  ?>
				<p>
					<label for="correct_choice">Correct Choice Number: </label>
					<input type="number" name="correct_choice" id="correct_choice" min="0" max="5" placeholder="<?php echo $correct_answer; ?>">
				</p>
				<p>
					<label for="correct_reason">Correct Reason: </label>
					<input type="text" name="correct_reason" id="correct_reason" placeholder="<?php echo $correct_reason; ?>">
				</p>
				<p>
					<label for="resources">Resources: </label>
					<input type="text" name="resources" id="resources" placeholder="<?php echo $resources; ?>">
				</p>
				<input type="submit" name="cancel" value="Cancel">
				<input type="submit" name="submit" value="submit">
			</form>
			
		</div>
	</main>


<?php include("includes/footer.php"); ?>