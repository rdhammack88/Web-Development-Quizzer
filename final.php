<?php
// Include dependencies and start session
include 'includes/connection.php';
session_start();
include 'includes/queries.php';
include("includes/header.php");

// Init page GLOBAL variables
//$category = $_SESSION["category"];
//$used_questions = $_SESSION["used_questions"];
//$answer_order	= $_SESSION["answer_order"];
$user_answers 	= $_SESSION['user_answers'];
$right_wrong 	= $_SESSION["right_wrong"];
$total 			= $_SESSION['total_questions'];
$score 			= $_SESSION['score'];
$score = floor((100/$total) * $score);
$class = $_SESSION['score'] < $total ? "wrong" : "correct";
//$used_questions = implode(", ", $used_questions);
//$answer_order	= implode(", ", $answer_order);
$number_of_rows = mysqli_num_rows($finale_queries);
$answer_ids = [];
$used_questions = [];

var_dump($right_wrong);
var_dump($user_answers);
var_dump($total);
var_dump($_SESSION['used_questions']);
var_dump($finale_queries);
echo implode(", ", $_SESSION["used_questions"]);


// Set final results message
if($_SESSION['score'] == $total) {
	$msg = "Flawless Victory!";
} elseif($_SESSION['score'] < $total && $score >= 70) {
	$msg = "Great job! Looks like you've been practicing.";
} elseif($score >= 60 && $total == 5) {
	$msg = "Great job! Looks like you've been practicing.";
} elseif($score  < 70 && $_SESSION['score'] > 0) {
	$msg = "Looks like you need some more practice...";
} else {
	$msg = "Well, {$_SESSION['category']} is not for everyone...";
}

/*$query = "SELECT questions.question_number, questions.question, questions.question_category, choices.id, choices.is_correct, choices.answers 
FROM questions 
LEFT JOIN choices ON questions.question_number = choices.question_number 
WHERE questions.question_category = '$category'
AND questions.question_number IN ($used_questions)
ORDER BY field(questions.question_number, $used_questions), field(id, $answer_order)";
$result = mysqli_query($conn, $query);*/

//$question_ids = [];

/*Debugging Purposes*/
//echo $answer_order;
//echo $number_of_rows;
//print_r($user_answers);
//echo "<br>"; ///////
//echo "<pre>";
//echo "Total: " . $total . "<br>";
//echo "Score: " . $score . "<br>";
//echo "Session Score: " . $_SESSION['score'] . "<br>";
//print_r($right_wrong);
//echo "</pre>" . "<br>";
//echo "<pre>";
//echo "Total: " . $total . "<br>";
//echo "Score: " . $score . "<br>";
//echo "Session Score: " . $_SESSION['score'] . "<br>";
//print_r($right_wrong);
//echo "</pre>" . "<br>";

?>

<main id="finalePage">
	<div class="container">
		<!-- Final test result details header -->
		<section id="finaleDetails">
			<!--<h2>You're Done!</h2>
			<p>Congrats! You have completed the test</p>-->
			<h2><?= $msg; ?></h2>
			<p>Final Score: &nbsp;&nbsp;<?= $score; ?>%</p>
			<p>Correct: &nbsp;&nbsp;&nbsp;&nbsp;<span class="<?= $class; ?>"><?= $_SESSION['score']; ?></span> of <span class="correct"><?= $total; ?></span> </p>
		</section>

<div class="wrapper">
<!-- Create the questions list -->
<ol id="question_list">
	<?php $i = 0; // Start a counter to help break out each answer group ?>
	<?php while($row = mysqli_fetch_assoc($finale_queries)) : ?>
	
	<?php 
	// Debugging Purposes
	/*print_r($user_answers);
	echo "<br>";
	echo $row['is_correct'];
	echo "<br>";*/
	if(in_array($row['id'], $user_answers) && $row['is_correct'] != 1) {
//		echo "Line:  " . __LINE__ . "<br>";
		$wrong_answer = "<div class=\"wrong_answer\">
						 <p>Wrong! </p>
						 <p>" . $row["answers"] . "</p>
						 </div>";
//		echo $row['answers'] . " - " . $row['id'];
	} 
//	else if(in_array($row['id'], $user_answers) && $row['is_correct'] == 1) {
		if($row['is_correct'] == 1 ) {
			
			$resource_list = explode(' ', $row['resources']);
//			print_r($resource_list);
			
		//echo "Line:  " . __LINE__ . "<br>";
		$correct_answer = "<div class=\"correct_answer\">
						   <p><span  class=\"correct\">Correct!</span> &nbsp;&nbsp;" . $row["answers"] . "</p>
						   <p>" . $row["correct_explanation"] . "</p>";
			
			
			if($row['resources'] == null) {
				$correct_answer .= "</div>";
			} else {
				$correct_answer .= "<h4>Resources: </h4>";
				foreach($resource_list as $resource) {
					
					// SHORTEN THE LINK NAME
					$firstRegEx = '/:\/\//';
					$secondRegEx = '/./';
					
					$correct_answer .= "<p class='resource'><a href=" . $resource . " target='_blank'>" . $resource . "</a></p>";
				}
				
				$correct_answer .= "</div>";
				
//				$correct_answer .= "<h4>Resources: </h4>
//						   			<p><a href=" . $row["resources"] . " target='_blank'>" . $row["resources"] . "</a></p>
//						   			</div>";
			}
						   
//		echo $row['answers'] . " - " . $row['id'];
	} 
		
//		if(isset($question_number_holder) && $row['question'] != $question_number_holder) {
//			 $i++;
//		}
	
	?>
	
	<!-- Display the questions that were answered -->
	<?php $question_number = $row['question_number'];  //$i++;?> <!-- $i++;  -->
	<?php
	if(!in_array($row['question'], $used_questions)) : ?>
	<?php
		/* Questions Associative Array Based on DB Stored Question Number */
//		$used_questions[$row['question_number']] = $row['question'];
//		$question_ids = []; /* Assoc Array based on question number */
//		array_push($question_ids, $question_number);
	
		$used_questions[$question_number] = $row['question'];
		$question_number_holder = $question_number;
//	echo $question_number;
//	 $i++;
	?>
	
	<!-- REMOVED CONTENT GOES HERE FOR ECHOING CORRECT/WRONG ANSWERS -->
		

<!-- Break out of answer list for each question -->
	<?php if($i > 1 ) : ?>
		</ol>
		<?php
		// If wrong answer is set (The user answered wrong), then display the wrong answer
		if(isset($wrong_answer)) {
			echo $wrong_answer;
			$wrong_answer = null;
		} 
		// If correct answer is set (The user answered correct), then display the correct answer
		if(isset($correct_answer)) {
			echo $correct_answer;
			$correct_answer = null;
		}

//	echo $correct_answer;
		?>
	<?php endif;  //$i++;?>
		
		
	<li class="question"><?= $row['question']; ?></li>
	<ol class="answer_list">
	<?php endif;  $i++;?>
	<?php 
	$answer_id = $row['id'];
	$answer_ids["$answer_id"] = $row['answers'];
	?>
	<?php //if(in_array($question_number, $question_ids)) : ?>
	<?php if($question_number === $question_number_holder) : ?>
		<?php if(in_array($row['id'], $user_answers) && $row['is_correct'] != 1) : ?>
	<li class="wrong"><?= $row['answers']; ?></li>
	<!--  . " - " . $row['id'] -->
		<?php elseif($row['is_correct'] == 1 ) : ?>
		<!-- && in_array($row['id'], $user_answers) -->
	<li class="correct"><?= $row['answers']; ?></li>
	<!--  . " - " . $row['id'] -->
		<?php //= "Line:  " . __LINE__ . "<br>"; ?>
		<?php //echo s$correct_answer; ?>
		<?php else : ?>
	<li><?= $row['answers'];  ?></li>	<!--  . " - " . $row['id'] -->
		<?php endif; ?>
	
	<?php //else:  //$i++; ?>
	
	

<!-- REMOVED CONTENT BELOW FOR ECHOING CORRECT/WRONG ANSWERS -->

<!-- REMOVED CONTENT ABOVE FOR ECHOING CORRECT/WRONG ANSWERS -->
	
	
	<?php //$i++; ?>

	<?php endif; ?>
	
	<?php if($i >= $number_of_rows) : ?>
	
		</ol>
		<?php
//		if(isset($wrong_answer))
		if(in_array($row['id'], $user_answers) && $row['is_correct'] != 1) {
			echo $wrong_answer;
		} 
		
		if(isset($correct_answer)) {
			echo $correct_answer;
		}
		
//	echo $correct_answer;
		?>

	
	<?php endif; ?>
		
	<?php
//		echo "Row id == " . $row['id'] . "<br>";
//		echo "Row correct == " . $row['is_correct'] . "<br>"; ?>
	<?php endwhile; ?>
	<?php //echo $correct_answer; ?>
</ol>
</div>

	<p><?php  ?></p>
	<p><?php  ?></p>
	   <?php ?>

		<a href="index.php" class="retake">Take Quiz Again</a>
<!--
		<br>
		<a href="tester3.php">Go to test page</a>
-->
	</div>
</main>
<?php include("includes/footer.php"); ?>
