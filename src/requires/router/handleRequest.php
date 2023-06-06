<?php
require_once "route.php";
session_start();
$url = $_POST['url'];
$_SESSION['url']=$url;
// Call the HandleRequest function with the URL
$response = ROUTER->HandleRequest($url);
// Return the response
return $response;
?>