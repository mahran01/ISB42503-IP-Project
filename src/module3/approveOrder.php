<?php
$page_title = "Order Approval";
$root_url = "../../";
$src = "../";

//@TODO Get Supplier ID here #############################################################################################
$supplierId = "2002";
//Get Supplier ID ends here ##############################################################################################

?>
<?php include_once("{$src}includes/header.html");?>
<h1>Order Approval</h1>
<h2>Supplier Id: <?php echo $supplierId ?></h2>
<?php include_once("approvingOrder.php");?>
<?php include_once("{$src}includes/footer.html");?>
