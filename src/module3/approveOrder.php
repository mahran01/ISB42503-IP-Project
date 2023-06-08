<?php
$page_title = "Order Approval";
$src = "../";

$mysqli = Route::MYSQL();
$supplierId = Authenticator::Supplier();
?>
<h1>Order Approval</h1>
<h2>Supplier Id: <?php echo $supplierId ?></h2>
<?php include_once("approvingOrder.php");?>
