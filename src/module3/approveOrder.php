<?php
$page_title = "Order Approval";
$src = "../";

$mysqli = Route::MYSQL();
//@TODO Get Supplier ID here #############################################################################################
$supplierId = "2001";
//Get Supplier ID ends here ##############################################################################################

?>
<h1>Order Approval</h1>
<h2>Supplier Id: <?php echo $supplierId ?></h2>
<?php include_once("approvingOrder.php");?>
