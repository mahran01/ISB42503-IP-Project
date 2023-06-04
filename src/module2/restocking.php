<?php
$page_title = "Restocking";
$root_url = "../../";
$src = "../";

//@TODO Get Supplier ID here #################################################################################################
$supplierId = "2001";
//Get Supplier ID ends here ##################################################################################################

?>
<?php include_once("{$src}includes/header.html");?>
<h1>Restocking</h1>
<h2>Supplier Id: <?php echo $supplierId ?></h2>
<?php //include_once("createNewItem.php");?>
<?php include_once("updateItem.php");?>
<?php include_once("{$src}includes/footer.html");?>
