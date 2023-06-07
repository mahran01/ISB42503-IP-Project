<?php # Script 9.4 - logout.php
// This page lets the user logout.

// Set the page title and include the HTML header.
$page_title = 'Logged Out!';

// Print a customized message.
echo "<h1>Logged Out!</h1>
<p>You are now logged out".(isset($_COOKIE['userName']) ? ", ".$_COOKIE['userName'] : "")."!</p>
<p><br /><br /></p>";

if(isset($_COOKIE['userName']))
{
    echo "<script>
    setTimeout(function(){
        window.location.reload();
    });
    </script>";
}

// Delete the cookies.
setcookie ('supplierId', '', time()-3600, '/');
setcookie ('agentId', '', time()-3600, '/');
setcookie ('userName', '', time()-3600, '/');
// session_destroy();
?>