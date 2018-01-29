<?php
//include("includes/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="Dustin Hammack">
	<meta name="description" content="Quiz site for Web Development Languages!">

	<!-- Mobile Stuff -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Quizzer</title>
	<link rel="stylesheet" href="../styles/main.css">
	
	<link rel="icon" type="image/png" href="">
</head>

<body>
	<header>
		<a href="index.php"><h1><?= isset($_SESSION["category"]) ? strtoupper($_SESSION["category"]) : "Quiz" ?> Tester</h1></a>
		<!--<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				
			</ul>
		</nav>-->
	</header>
	


