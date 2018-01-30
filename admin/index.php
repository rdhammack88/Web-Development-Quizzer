<?php 
require_once("../includes/connection.php");
require_once("../includes/queries.php");
session_start();

if(!isset($_SESSION['active_admin'])) {
	header("Location: admin_login.php");
}

//var_dump($_SESSION);

$admin_user = $_SESSION['active_admin'];
$start = 0;
$end = 10;
$total_count = mysqli_num_rows($results);

if(!isset($_SESSION['start'])) {
	$_SESSION['start'] 			= $start;
	$_SESSION['end']			= $end;
	$_SESSION['total_count']	= $total_count;
} else {
	$start 	= $_SESSION['start'];
	$end	= $_SESSION['end'];
}

if(isset($_SESSION['start']) && isset($_SESSION['end'])) {
	if(isset($_POST['next'])) {
		$start = $_SESSION['start'] + 10;
		$end = $_SESSION['end'] + 10;
		$_SESSION['start']	= $start;
		$_SESSION['end']	= $end;
	}

	if(isset($_POST['previous'])) {
		$start = $_SESSION['start'] - 10;
		$end = $_SESSION['end'] - 10;
		$_SESSION['start']	= $start;
		$_SESSION['end']	= $end;
	}
}

$query 		= "SELECT * 
			   FROM `questions`
			   WHERE question_number <= '$end' 
			   AND question_number > '$start'
			   AND added_by = '$admin_user'
			   LIMIT 10";
$questions 	= mysqli_query($conn, $query);

//if(isset($_POST['edit'])) {
//	$start 	= 0;
//	$end	= 10;
//	$_SESSION['start'] 	= $start;
//	$_SESSION['end']	= $end;
//	$_SESSION['total_count'] = mysqli_num_rows($results);
//}

//if(isset($_SESSION['start']) && isset($_SESSION['end'])) {
//	$start = $_SESSION['start'];
//	$end = $_SESSION['end'];
//	$total_count = $_SESSION['total_count'];
//}

require_once("../includes/header.php");
?>

<main id="adminPage">

<?php if(mysqli_num_rows($questions) >= 1) : ?>
	<p><a href="add.php" role="button" class="btn">Add Question</a></p>
<?php while($row = mysqli_fetch_assoc($questions)) : ?>
	<div class="question_box">
		<p class="question_number">Question Number: <?php echo $row['question_number']; ?></p>
		<p class="edit-question">
			<a href="edit.php?question_number=<?= $row['question_number']; ?>">✎</a>
		</p>
		<!--<button></button>
		<!--  . "&category=" . $row['question_category'] -->
		
		<p><?php echo $row['question']; ?></p>
		
	</div>
<?php endwhile; ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php if($end >= 20) : ?>
	<button type="submit" name="previous">⇦</button>
<?php endif; ?>
<?php if($end < $total_count) : ?>
	<button type="submit" name="next">⇨</button>
<?php endif; ?>
</form>
<!-- ➜ -->
<?php else : ?>
<div class="no-user-questions">
	<h3>You currently have not added any questions.</h3>
	<p><a href="add.php" role="button" class="btn">Add Question</a></p>
</div>
<?php endif; ?>

</main>




<?php require_once("../includes/footer.php"); ?>