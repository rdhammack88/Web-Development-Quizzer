<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/queries.php"); ?>
<?php require_once("includes/header.php"); ?>
<?php
session_start();

$_SESSION['score'] = 0;
$_SESSION['used_questions'] = [];
$_SESSION['user_answers'] = [];
$_SESSION['answer_order'] = [];
$_SESSION["right_wrong"] = [];

?>

	<main class="testChoice">
		<div class="container">
			<h3>Choose your test type</h3>
			<!-- &cat=css -->
			<form action="questions.php?n=1" method="post">
				<label for="category">Test Category:</label>
				<select name="category" id="category" required>
					<option value="none" selected disabled>Select One...</option>
					
					<?php if( mysqli_num_rows( $categories ) > 0 ) { ?>
					<?php while( $row = mysqli_fetch_assoc($categories) ) : ?>
						
						<option value="<?= $row['question_category']; ?>">
							<?= strtoupper($row['question_category']); ?>
						</option>
					<?php endwhile; ?>
					<?php } ?>
				</select>
				<br/><br/>
				<label for="number_of_questions">Number of Questions:</label>
				<select name="number_of_questions" id="number_of_questions" required>
					<?php 
						//$question_count = $_SESSION['question_count'];
					?>
				</select>
				<br/><br/>
				<label for="type">Test Type:</label>
				<span id="type">Multiple Choice</span>
				<br/><br/>
				<label for="time">Estimated Time:</label>
				<span id="time"></span>
				<br/><br/><br/>
				<button type="submit" name="start" class="start">Start Quiz</button>
			</form>
		</div>
	</main>

<?php include("includes/footer.php"); ?>