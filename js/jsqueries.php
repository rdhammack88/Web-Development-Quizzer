<?php require_once("../includes/connection.php"); ?>
<?php

if(isset($_GET['category'])) {
	$category = $_GET['category'];
	$query = "SELECT * 
			  FROM `questions`
			  WHERE `question_category` = '$category'";
	$results = mysqli_query($conn, $query);	
	//$categories = mysqli_fetch_assoc($results);	
	$question_count = mysqli_num_rows($results);
	echo $question_count;
}

?>