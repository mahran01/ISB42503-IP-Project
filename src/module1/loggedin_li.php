<?php # Script 9.2 - loggedin.php
# User is redirected here from login.php.

// Set the page title and include the HTML header.
$page_title = 'Logged In!';

// Print a customized message.
echo "<h1>Logged In!</h1>
<p>You are now logged in, {$_COOKIE['username']} {$_COOKIE['adminId']}!</p>
<p><br /><br /></p>";
?>