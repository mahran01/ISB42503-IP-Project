<?php
	$page_title = 'Record Order list';
	$dbc = Route::MYSQL_PROCEDURAL();
?>
<style>
.custom-table {
    width: 100%;
    max-width: 1500px; 
    border-collapse: collapse;
    border: 1px solid #ccc;
}

.custom-table th,
.custom-table td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ccc;
}

.custom-table th {
    background-color: #f2f2f2;
}

.custom-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.custom-table tr:hover {
    background-color: #eaeaea;
}
</style>
<h2>Record Order List</h2>
<?php
	$q = "SELECT * FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2";
	$r = mysqli_query($dbc, $q) or die ('error');
	$num = mysqli_num_rows($r);
        if(mysqli_num_rows($r) > 0){
	echo '<table border="3" align="center" cellspacing="2" cellpadding="5" class="custom-table">
                <tr>
					<td align="left"><b>Sales Order ID</b></td>
					<td align="left"><b>Customer Name</b></td>
					<td align="left"><b>Contact Number</b></td>
					<td align="left"><b>Date Create</b></td>
					<td align="left"><b>Created By</b></td>
					<td align="left"><b>Item ID</b></td>
					<td align="left"><b>Item Name</b></td>
					<td align="left"><b>Quantity</b></td>
                </tr>';
	
	$prev = "";
	while ($row = mysqli_fetch_assoc($r)){

		$isSame = $prev === $row['SalesOrderId'];
		
		$salesOrderId = $isSame ? "" : $row['SalesOrderId'];
		$customerName = $isSame ? "" : $row['CustomerName'];
		$contactNumber = $isSame ? "" : $row['ContactNumber'];
		$dateCreated = $isSame ? "" : $row['DateCreated'];
		$createdBy = $isSame ? "" : $row['CreatedBy'];

		echo '<tr>
			<td align="left">' .$salesOrderId. '</td>
			<td align="left">' .$customerName.'</td>
			<td align="left">' .$contactNumber. '</td>
			<td align="left">' .$dateCreated. '</td>
			<td align="left">' .$createdBy. '</td>
			<td align="left">' . $row['ItemId'] . '</td>
			<td align="left">' . $row['ItemName'] . '</td>
			<td align="left">' . $row['Quantity'] . '</td>
		</tr>';

		$prev = $salesOrderId;
	}
	} 
	echo '</table>';

	mysqli_free_result($r); // Free up the resources.
	mysqli_close($dbc); // Close the database connection.

?>