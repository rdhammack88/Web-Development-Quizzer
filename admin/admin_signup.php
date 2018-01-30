<?php
require_once('../includes/connection.php');
require_once('../includes/functions.php');
require_once('../includes/queries.php');
$email_error = '';
$passwordError = '';
$passed = "Not changed yet";

if(isset($_POST['signup'])) {
	if( !$_POST["email"] ) {
		$email_error = "Please enter your email";
		if( !$_POST["password"] ) {
			$passwordError = "Please enter a password";
		}
	} else {
		$user_email = validateFormData($_POST['email']);
		$query = "SELECT email
				  FROM admin_users
				  WHERE email = '$user_email'";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) >= 1) {
			$email_error = "Email already in use, please choose a different email";
		} else {
			if(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)) {
				$user_email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
				$user_email = validateFormData($user_email);
			}
			
			if( !$_POST["password"] ) {
				$passwordError = "Please enter a password";
			} else {
				$user_password = password_hash( $_POST['password'], PASSWORD_DEFAULT );
			}

			if($user_email && $user_password) {	
				$query = "INSERT INTO admin_users (email, password)
						  VALUES('$user_email', '$user_password')";

				/* If user info has been accepted and inserted into DB */
				if( $result = mysqli_query( $conn, $query ) )	{
					$user_id = mysqli_insert_id($conn);
					$_SESSION['active_admin'] = $user_id;
					header("Location: ../admin/index.php");
				}
			}
		}
	}	
}

include('../includes/header.php');
?>

<main id="adminSignup">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<fieldset>
			<legend>Sign up</legend>
			<div class="input-group">
				<label for="email">Email:</label>
				<input type="text" name="email" placeholder="email">
				<p class="hidden text-error"><small><?php echo $email_error; ?></small></p>
			</div>
			<div class="input-group">
				<label for="password">Password:</label>
				<input type="password" name="password" placeholder="password">
				<p class="hidden text-error"><small><?php echo $passwordError; ?></small></p>
			</div>
			<p>
				<button type="submit" name="signup" class="btn">Signup</button>
			</p>			
		</fieldset>
	</form>

	<p>Have an account? &nbsp;<span><a href="../admin/admin_login.php"> Login!</a></span></p>
</main>
<?php include('../includes/footer.php'); ?>