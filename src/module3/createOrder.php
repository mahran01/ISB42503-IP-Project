<?php
$page_title = "Create order";

$mysqli = Route::MYSQL();
$agentId = Authenticator::Agent();

?>
<h1>Create Order</h1>
<h2>Agent Id: <?php echo $agentId ?></h2>
<?php include_once("creatingOrder.php") ?>
