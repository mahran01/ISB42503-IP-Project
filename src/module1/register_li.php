<?php # Script 7.7 - register.php (3rd version after Scripts 7.3 & 7.5)

$page_title = 'Register';
include ('./s_includes/header.html');

if (! isset ($_COOKIE ['UserId'])||$_COOKIE ['RoleId']!=2){
   exit();
}
 $supplierid= $_COOKIE['UserId'];



 // Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	require_once ('mysqli.php'); // Connect to the db.
		
	global $dbc;
	
	/*function escape_data ($data) {
			
		if (ini_get('magic_quotes_gpc')) {
				$data = stripslashes($data);
		}
		return mysql_real_escape_string(trim($data));
	} // End of function.*/

	$errors = array(); // Initialize error array.
	
	// Check for an email address.
	if (empty($_POST['username'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = ($_POST['username']);
	}
	if (empty($_POST['UserID'])) {
		$errors[] = 'You forgot to enter your UserID.';
	} else {
		$ID = ($_POST['UserID']);
	}
	if (empty($_POST['ICnumber'])) {
		$errors[] = 'You forgot to enter your ICnumber.';
	} else {
		$IC = ($_POST['ICnumber']);
	}
	if (empty($_POST['LoginName'])) {
		$errors[] = 'You forgot to enter your LoginName.';
	} else {
		$LoginName = ($_POST['LoginName']);
	}
	
	// Check for a password and match against the confirmed password.
	if (!empty($_POST['password1'])) {
		if ($_POST['password1'] != $_POST['password2']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$p = ($_POST['password1']);
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}
	
	if (empty($errors)) { // If everything's okay.
	
		// Register the user in the database.
		
		// Check for previous registration.
		$query = "SELECT * FROM user_login_data WHERE LoginName= $LoginName AND UserID=$ID;";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		if (mysqli_num_rows($result) == 0) {

			$query = "INSERT INTO user_account VALUES ( '$ID','$e','$IC',3 )";		
			$result = @mysqli_query ($dbc,$query); // Make the query.
			$query = "INSERT INTO user_login_data VALUES ( '$ID','$LoginName','$p' )";
			$result = @mysqli_query ($dbc,$query); // Make the query.
			$query = "INSERT INTO role_agent VALUES ( '$ID','$supplierid')";
			$result = @mysqli_query ($dbc,$query); // Run the query. // Run the query.
			if ($result) { // If it ran OK. == IF TRUE
			
				// Send an email, if desired.
				
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>You are now registered. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./a_includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./a_includes/footer.html'); 
				exit();
			}
				
		} else { // Already registered.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The email address has already been registered.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.
		
}// End of the main Submit conditional.
?>
<h2>Register</h2>
<form action="register_li.php" method="post">
	<p>UserID: <input type="UserID" name="UserID" size="10" maxlength="20" /></p>
    <p>ICnumber: <input type="ICnumber" name="ICnumber" size="10" maxlength="20" /></p>
	<p>LoginName: <input type="text" name="LoginName" size="20" maxlength="40" value="<?php if (isset($_POST['LoginName'])) echo $_POST['LoginName']; ?>"  /> </p>
	<p>username: <input type="text" name="username" size="20" maxlength="40" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>"  /> </p>
	<p>Password: <input type="password" name="password1" size="10" maxlength="20" /></p>
	<p>Confirm Password: <input type="password" name="password2" size="10" maxlength="20" /></p>
	<p><input type="submit" name="submit" value="Register" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<?php
include ('./a_includes/footer.html');
?>