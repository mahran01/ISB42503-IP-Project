<?php # Script 9.4 - logout.php
// This page lets the user logout.

// Set the page title and include the HTML header.
$page_title = 'Logged Out!';
include ('./u_includes/header.html');

// Print a customized message.
echo "<h1>Logged Out!</h1>
<p>You are now logged out, {$_COOKIE['first_name']}!</p>
<p><br /><br /></p>";

// Delete the cookies.
//setcookie ('first_name', '', time()-300, '/', '', 0);
//setcookie ('user_id', '', time()-300, '/', '', 0);
$_COOKIE = [];
print_r ($_COOKIE);

include ('./u_includes/footer.html');
?>