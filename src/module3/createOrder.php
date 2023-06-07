<?php
$page_title = "Create order";

$mysqli = Route::MYSQL();
//@TODO Get Agent ID here #############################################################################################
$agentId = "3001";
//Get Agent ID ends here ##############################################################################################

?>
<h1>Create Order</h1>
<h2>Agent Id: <?php echo $agentId ?></h2>
<?php include_once("creatingOrder.php") ?>
