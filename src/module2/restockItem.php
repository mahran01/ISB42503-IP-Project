<?php
$page_title = "Restocking";

$mysqli = Route::MYSQL();

$supplierId = Authenticator::Supplier();

?>
<h1>Restocking</h1>
<h2>Supplier Id: <?php echo $supplierId ?></h2>
<?php include_once("restockingItem.php");?>
