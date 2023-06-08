<?php # Script 9.1 - login.php
// Send NOTHING to the Web browser prior to the setcookie() lines!

$dbc = Route::MYSQL_PROCEDURAL();
// Check if the form has been submitted.
if (isset($_POST['submit'])) {

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
		$row = mysqli_fetch_assoc ($result); // Return a record, if applicable.
		

		if ($row) { // A record was pulled from the database.
			$_SESSION = [];
			if ($row['RoleId'] == 2)
			{
				setcookie('supplierId', $row['UserId'], time() + 3600, '/');
				setcookie('agentId', '', time() - 3600, '/');
                $_SESSION['url'] = '/supplierHome';
			}
			elseif ($row['RoleId'] == 3)
			{
				setcookie('agentId', $row['UserId'], time() + 3600, '/');
				setcookie('supplierId', '', time() - 3600, '/');
                $_SESSION['url'] = '/agentHome';
			}
			setcookie('userName', $row['UserName'], time() + 3600, '/');

			echo '<h1>Logged in</h1>';
			echo "<p>Hello ".$row['UserName']." </p>";

			// // Redirect the user to the loggedin.php page.
			// // Start defining the URL.
			// if ($role == 2)
			// ROUTER->handleRequest("/supplierHome");
			// elseif ($role == 3)
            
            //@TODO check for login
			ROUTER->handleRequest("/refresh");
            
            echo "<script>
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000);
                </script>";
            exit();
				
		} else { // No record matched the query.
			$errors[] = 'The Login Name and Login Password entered do not match those on file.'; // Public message.
			// $errors[] = mysqli_error($dbc)  . '<br /><br />Query: ' . $query; // Debugging message.
		}
		
	} // End of if (empty($errors)) IF.
		
	mysqli_close($dbc); // Close the database connection.

} else { // Form has not been submitted.

	$errors = NULL;

} // End of the main Submit conditional.

// Begin the page now.
$page_title = 'Login';

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
<form action="" method="post">
	<p>LoginName: <input type="text" name="LoginName" size="20" maxlength="40" /> </p>
	<p>LoginPassword: <input type="LoginPassword" name="LoginPassword" size="20" maxlength="20" /></p>
    <label class="form-label">Select User Type:</label>
    <select class="form-select mb-3"
    name="role" 
    aria-label="Default select example">
        <option selected value="2">Supplier</option>
        <option value="3">Agent</option>
        <option value="1">Admin</option>
    </select>
<p><input type="submit" name="submit" value="Login" /></p>
</form>