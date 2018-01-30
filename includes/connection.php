<?php

// Create Database Variables
$server = "localhost";
$user	= "Dustin";
$pw		= "rusty";
$db		= "quizzer";

// Connect to Database
$conn	= mysqli_connect($server, $user, $pw, $db);

// Verify Connection to Database
if(!$conn) {
	die("Connection to database failed: " . mysqli_connect_error());
}
