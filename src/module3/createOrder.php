<?php
$page_title = "Create order";
$root_url = "../../";
$src = "../";

//@TODO Get Agent ID here #############################################################################################
$agentId = "3001";
//Get Agent ID ends here ##############################################################################################

?>
<?php include_once("{$src}includes/header.html");?>
<h1>Create Order</h1>
<h2>Agent Id: <?php echo $agentId ?></h2>
<?php include_once("creatingOrder.php") ?>
<?php include_once("{$src}includes/footer.html");?>
