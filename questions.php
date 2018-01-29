<?php require_once("includes/connection.php"); ?>
<?php require("includes/queries.php"); ?>
<?php
session_start();

if(!isset($_GET["n"])) {  // || $_GET["n"] != 1
	header("Location: index.php");
}

// Check to see if score is set
if(!isset($_SESSION['score'])) {
	$_SESSION['score'] = 0;
}

/*
 * Choice Page On Submit
*/
if(isset($_POST["start"])) { //or isset($_POST["submit"])
	// Set question number
	$number = (int) $_GET['n'];
	$category = $_POST["category"]; // Set test category
	$_SESSION["category"] = $category;
	$_SESSION["total_questions"] = $_POST["number_of_questions"]; // Set number of test questions
	$total = $_SESSION["total_questions"];
	// Set error for no choices selected
	$choicesError = '';
	
	/*
	 * Get Question
	*/
	$query = "SELECT * FROM `questions`
		  WHERE `question_category` = '$category'
		  ORDER BY RAND()
		  LIMIT 1";

	// Get Result
	$result = mysqli_query($conn, $query) or die();
	$question = mysqli_fetch_assoc($result);

	/*
	 * Get Choices
	*/
	$question_number = $question["question_number"];
	$query = "SELECT * FROM `choices`
			  WHERE question_number = $question_number
			  ORDER BY RAND()";
	// Get Results
	$choices = mysqli_query($conn, $query) or die();
	array_push($_SESSION["used_questions"], $question_number);
} elseif(isset($_GET['qn'])) {
	// Get the question id from the DB
	$question_number = $_GET['qn'];
	// Set question number
	$number = $_GET['n'];
	// Set category
	$category = $_SESSION["category"];
	// Set error for no choices selected
	$choicesError = "Please choose an answer before submitting";
	
	/*
	 * Get Question
	*/
	$query = "SELECT * FROM `questions`
			  WHERE `question_category` = '$category'
			  ORDER BY RAND()
			  LIMIT 1";
	// Get Result
	$result = mysqli_query($conn, $query) or die();
	$question = mysqli_fetch_assoc($result);
	
	/*
	 * Get Choices
	*/
	$query = "SELECT * FROM `choices`
			  WHERE question_number = $question_number
			  ORDER BY RAND()";
	// Get Results
	$choices = mysqli_query($conn, $query);
} elseif(isset($_GET['n'])) {
	//// Set question number
	$number = (int) $_GET["n"];
	// Set category
	$category = $_SESSION["category"];
	// Set error for no choices selected
	$choicesError = '';

	/*
	 * Get Question
	*/
	do {
		$query = "SELECT * FROM `questions`
			  WHERE `question_category` = '$category'
			  ORDER BY RAND()
			  LIMIT 1";
		// Get Result
		$result = mysqli_query($conn, $query) or die();
		$question = mysqli_fetch_assoc($result);
	} while(in_array($question["question_number"], $_SESSION["used_questions"]));

	// Set question number
	$question_number = $question["question_number"];
	array_push($_SESSION["used_questions"], $question_number);

	/*
	 * Get Choices
	*/
	$query = "SELECT * FROM `choices`
			  WHERE question_number = $question_number
			  ORDER BY RAND()";
	// Get Results
	$choices = mysqli_query($conn, $query) or die();
} else {
	header("Location: choice.php");
}

?>
<?php require_once("includes/header.php"); ?>
	<main class="questionPage">
		<div class="container">
			<form method="POST" action="process.php">
				<div class="question">
					<p class="current">Question <?= $number; ?> of <?= $_SESSION["total_questions"]; ?></p>
					
					<p><span id="question_number"><?= $number . ". "; ?></span><?=  $question['question']; ?></p>
				</div>

				<div class="answers">
					<ul class="choices">
						<?php while($row = mysqli_fetch_assoc($choices)) : ?>
							<li><input type="radio" name="choice" value="<?= $row['id']; ?>" id="<?= $row['id']; ?>">&nbsp;&nbsp;<label for="<?= $row['id']; ?>"><?= $row['answers']; ?></label></li>
							<?php array_push($_SESSION['answer_order'], $row['id']); ?>
						<?php endwhile; ?>					
					</ul>
					<br>
					<p class="choicesError"><?= $choicesError; ?></p>
				</div>

				<div class="submission">
					<input type="submit" name="submit" value="Next" id="submitAnswer">
					<input type="submit" name="quit" value="Quit">
					<input type="hidden" name="number" value="<?= $number; ?>">
					<input type="hidden" name="questionNumber" value="<?= $question_number; ?>">
				</div>
			</form>		
		</div>
	</main>
	
<?php include("includes/footer.php");