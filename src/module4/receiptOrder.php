<?php
	$page_title = 'Reciept Order';
	[$mysqli, $dbc] = Route::MYSQL_BOTH();
?>
<h2>Reciept Order</h2>
<form action="" method="post">
	<p>Sales Order Id: 
	<?php
		$resultSet= $mysqli->query("SELECT DISTINCT SalesOrderId FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2");
	?>
		<select name ="salesOrderId">
		<!-- <option value="" >Select Sales Order Id</option> -->
			<?php
				while ($row = $resultSet->fetch_assoc())
				{   
					$salesOrderId= $row["SalesOrderId"];
				echo "<option value='$salesOrderId'>$salesOrderId</option>";
				}
			?>
		</select>
	<p><input type="submit" name="submit" value="Check" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php

if (isset($_POST['submitted'])) {
		
    /*function escape_data ($data) {
            
        if (ini_get('magic_quotes_gpc')) {
                $data = stripslashes($data);
        }
        return mysql_real_escape_string(trim($data));
    } // End of function.*/

    $errors = array(); // Initialize error array.

    if (empty($_POST['salesOrderId'])) {
        $errors[] = 'You forgot to select salesOrderId.';
    } else {
        $salesOrderId = ($_POST['salesOrderId']);
    }

    $q = "SELECT * FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2 AND sales_order.SalesOrderId = $salesOrderId";
    $r = mysqli_query($dbc, $q) or die ('error');
	$num = mysqli_num_rows($r);
        
    echo 'Sales Order Id:' .$num['SalesOrderId'].'';
        
}

	// $q = "SELECT * FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2";
	// $r = mysqli_query($dbc, $q) or die ('error');
	// $num = mysqli_num_rows($r);
    //     if(mysqli_num_rows($r) > 0){
	// echo '<table border="3" align="center" cellspacing="2" cellpadding="5">
    //             <tr>
	// 				<td align="left"><b>Sales Order ID</b></td>
	// 				<td align="left"><b>Customer Name</b></td>
	// 				<td align="left"><b>Contact Number</b></td>
	// 				<td align="left"><b>Date Create</b></td>
	// 				<td align="left"><b>Created By</b></td>
	// 				<td align="left"><b>Item ID</b></td>
	// 				<td align="left"><b>Item Name</b></td>
	// 				<td align="left"><b>Quantity</b></td>
    //             </tr>';
	
	// $prev = "";
	// while ($row = mysqli_fetch_assoc($r)){

	// 	$isSame = $prev === $row['SalesOrderId'];
		
	// 	$salesOrderId = $isSame ? "" : $row['SalesOrderId'];
	// 	$customerName = $isSame ? "" : $row['CustomerName'];
	// 	$contactNumber = $isSame ? "" : $row['ContactNumber'];
	// 	$dateCreated = $isSame ? "" : $row['DateCreated'];
	// 	$createdBy = $isSame ? "" : $row['CreatedBy'];

	// 	echo '<tr>
	// 		<td align="left">' .$salesOrderId. '</td>
	// 		<td align="left">' .$customerName.'</td>
	// 		<td align="left">' .$contactNumber. '</td>
	// 		<td align="left">' .$dateCreated. '</td>
	// 		<td align="left">' .$createdBy. '</td>
	// 		<td align="left">' . $row['ItemId'] . '</td>
	// 		<td align="left">' . $row['ItemName'] . '</td>
	// 		<td align="left">' . $row['Quantity'] . '</td>
	// 	</tr>';

	// 	$prev = $salesOrderId;
	// }
	// } 
	// echo '</table>';

	// mysqli_free_result($r); // Free up the resources.
	// mysqli_close($dbc); // Close the database connection.

?>