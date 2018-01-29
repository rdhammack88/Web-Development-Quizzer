<?php
require_once('../includes/connection.php');
require_once('../includes/functions.php');
//require_once('../includes/queries.php');
session_start();
session_unset(); 
session_destroy();
session_start();

//// if login form was submitted
//if( isset( $_POST['login'] ) ) {
//	
//	// create variables
//	// wrap data with validate function
//	$formEmail	= validateFormData( $_POST['email'] );
//	$formPass	= validateFormData( $_POST['password'] );
///* verify hashed password with submitted password */
//		if( password_verify( $formPass, $hashedPass ) ) {
//			
//		}
//}




if(isset($_POST['login'])) {
	$user_email = validateFormData($_POST['email']);
	$user_password = validateFormData($_POST['password']);
	$query = "SELECT *
			  FROM admin_users
			  WHERE email = '$user_email'";
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_assoc($result)){
		$hashedPass = $row['password'];
		
		
		if(password_verify($user_password, $hashedPass)) {
//			echo $user_password;
//			echo $row['id'];
//			exit();
			
			$_SESSION['active_admin'] = $row['id'];
//			var_dump($_SESSION);
//			exit();
			header("Location: index.php");
		}
		
		echo $hashedPass;
		exit();
	}	
}

include('../includes/header.php');
?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Log In</legend>
		<label for="email">Email:</label>
		<input type="text" name="email" placeholder="email">
		<label for="password">Password:</label>
		<input type="password" name="password" placeholder="password">
		<button type="submit" name="login" class="btn">Submit</button>
	</fieldset>
</form>

<p>Don't have an account? <span><a href="../admin/admin_signup.php">Create one!</a></span></p>


<?php include('../includes/footer.php'); ?>