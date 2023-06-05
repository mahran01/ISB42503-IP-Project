<?php # Script 9.1 - login.php
// Send NOTHING to the Web browser prior to the setcookie() lines!

// Check if the form has been submitted.
if (isset($_POST['submit'])) {

	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

/*	function escape_data ($data) {
			
		if (ini_get('magic_quotes_gpc')) {
				$data = stripslashes($data);
		}
	//	return mysql_real_escape_string(trim($data));
	} // End of function.	*/
	
		
	$errors = array(); // Initialize error array.
	
	// Check for an email address.
	if (empty($_POST['LoginName'])) {
		$errors[] = 'You forgot to enter your LoginName.';
	} else {
		$LoginName = ($_POST['LoginName']);
	}
	
	// Check for a LoginPassword.
	if (empty($_POST['LoginPassword'])) {
		$errors[] = 'You forgot to enter your LoginPassword.';
	} else {
		$p = ($_POST['LoginPassword']);
	}
	if (empty($_POST['role'])) {
		$errors[] = 'You forgot to enter your role.';
	} else {
		$role = ($_POST['role']);
	}
	
	if (empty($errors)) { // If everything's OK.
		/* Retrieve the user_id and first_name for 
		that email/LoginPassword combination. */
		$query = "SELECT * FROM user_login_data JOIN user_account USING (UserId) WHERE LoginName='$LoginName' AND LoginPassword=('$p') AND RoleId=$role";		
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$row = mysqli_fetch_array ($result, MYSQLI_NUM); // Return a record, if applicable.
		

		if ($row) { // A record was pulled from the database.
				
			// Set the cookies & redirect.
			setcookie ('UserId', $row[0]);
			setcookie ('LoginName', $row[1]);
			setcookie ('RoleId', $row[5]);
			setcookie ('username', $row[3]);//joe

			echo '<h1> Logged in </h1>';
		echo "<p> hello ".$_COOKIE ["username"]." </p>";
			
			//setcookie ('user_id', $row[0], time()+3600, '/', '', 0);
			//setcookie ('first_name', $row[1], time()+3600, '/', '', 0);
			
			
			// Redirect the user to the loggedin.php page.
			// Start defining the URL.
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
			// Check for a trailing slash.
			if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
				$url = substr ($url, 0, -1); // Chop off the slash.
			}
			if ($role == 3)
			$url .= '/agent.php';
			
			elseif ($role == 2)
			$url .= '/supplier.php';
			
			header("Location: $url");
			
			//echo'<br><a href="http://localhost/ip/cookies/loggedin.php">Logged in</a>';
			exit(); // Quit the script.
				
		} else { // No record matched the query.
			$errors[] = 'The Login Name and Login Password entered do not match those on file.'; // Public message.
			$errors[] = mysqli_error($dbc)  . '<br /><br />Query: ' . $query; // Debugging message.
		}
		
	} // End of if (empty($errors)) IF.
		
	mysqli_close($dbc); // Close the database connection.

} else { // Form has not been submitted.

	$errors = NULL;

} // End of the main Submit conditional.

// Begin the page now.
$page_title = 'Login';
include ('./u_includes/header.html');

if (!empty($errors)) { // Print any error messages.
	echo '<h1 id="mainhead">Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errors as $msg) { // Print each error.
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

// Create the form.
?>
<h2>Login</h2>
<form action="login_li.php" method="post">
	<p>LoginName: <input type="text" name="LoginName" size="20" maxlength="40" /> </p>
	<p>LoginPassword: <input type="LoginPassword" name="LoginPassword" size="20" maxlength="20" /></p>
	<p><input type="submit" name="submit" value="Login" /></p>
			<label class="form-label">Select User Type:</label>
		  <select class="form-select mb-3"
		          name="role" 
		          aria-label="Default select example">
			  <option selected value="2">Supplier</option>
			  <option value="3">Agent</option>
			  <option value="1">Admin</option>
			  
		  </select>
</form>
<?php
include ('./u_includes/footer.html');
?>