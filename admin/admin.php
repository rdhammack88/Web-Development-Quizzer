<?php require_once("/includes/connection.php"); ?>
<?php require_once("includes/queries.php"); ?>
<?php require_once("includes/header.php"); ?>


<?php
//$start 	= 0;
//$end 	= 10;
//
//if(isset($_POST['edit'])) {
//	
//} else {
//	header("Location: index.php");
//	exit();
//}
//
//if(isset($_GET['previous'])) {
//	
//}
//
//if(isset($_GET['next'])) {
//	$start 	+= 10;
//	$end 	+= 10;
//}
//
//$query 		= "SELECT * FROM `questions` WHERE question_number <= 20 AND question_number > 10 LIMIT 10";
//$results 	= mysqli_query($conn, $query);

//$start = $GLOBALS['start'];
//$end = $GLOBALS['end'];

session_start();


if(isset($_POST['edit'])) {
//	$GLOBALS['start'] 	= 0;
//	$GLOBALS['end'] 	= 10;
	
	$start 	= 0;
	$end	= 10;
	$_SESSION['start'] 	= $start;
	$_SESSION['end']	= $end;
	$_SESSION['total_count'] = mysqli_num_rows($results);
//	$_SESSION['num']	= 0;
}
//} else {
//	header("Location: index.php");
//	exit();
//}

if(isset($_POST['previous'])) {
//	$GLOBALS['start']	-= 10;
//	$GLOBALS['end']	-= 10;
	
//	echo "Line:  " . __LINE__ . "<br>";
//	
//	echo $start;
//	echo "<br>";
//	echo $end;
//	echo "<br><br>";
//	
	
	$_SESSION['start']	= $_SESSION['start'] - 10;
	$_SESSION['end']	= $_SESSION['end'] - 10;
	
	
//	echo $start;
//	echo "<br>";
//	echo $end;
//	echo "<br><br>";
	
	
}

if(isset($_POST['next'])) {
//	$GLOBALS['start'] 	+= 10;
//	$GLOBALS['end'] 	+= 10;
//	echo "Line:  " . __LINE__ . "<br>";
//	echo $_SESSION['num'];
//	echo "<br>";
//	$_SESSION['num']++;
//	echo $_SESSION['num'];
//	echo "<br><br>";
	
//	echo $start;
//	echo "<br>";
//	echo $end;
//	echo "<br><br>";
	
	 $_SESSION['start']	= $_SESSION['start'] + 10;
	 $_SESSION['end']	= $_SESSION['end'] + 10;
	
	
//	echo $start;
//	echo "<br>";
//	echo $end;
//	echo "<br><br>";
	
}

if(isset($_SESSION['start']) && isset($_SESSION['end'])) {
	$start = $_SESSION['start'];
	$end = $_SESSION['end'];
	$total_count = $_SESSION['total_count'];
}



$query 		= "SELECT * FROM `questions` WHERE question_number <= $end AND question_number > $start LIMIT 10";
//$query 		= "SELECT questions.question_number, questions.question, 
//			   questions.question_category, choices.id,
//			   choices.is_correct, choices.answers 
//			   FROM questions 
//			   LEFT JOIN choices ON questions.question_number = choices.question_number
//			   WHERE questions.question_number <= $end 
//			   AND questions.question_number > $start 
//			   LIMIT 10";
$questions 	= mysqli_query($conn, $query);

//echo $start;
//echo "<br>";
//echo $end;
//echo "<br>";


?>

<main id="adminPage">


<?php while($row = mysqli_fetch_assoc($questions)) : ?>
	<div class="question_box">
		<p class="question_number">Question Number: <?php echo $row['question_number']; ?></p>
		<a href="edit.php?question_number=<?= $row['question_number']; ?>">✎</a>
		<!--<button></button>-->
		<!--  . "&category=" . $row['question_category'] -->
		
		<p><?php echo $row['question']; ?></p>
		
	</div>
<?php endwhile; ?>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<?php if($end >= 20) : ?>
	<button type="submit" name="previous">⇦</button>
<?php endif; ?>
<?php if($end < $total_count) : ?>
	<button type="submit" name="next">⇨</button>
<?php endif; ?>
</form>
<!-- ➜ -->

</main>




<?php require_once("includes/footer.php"); ?>