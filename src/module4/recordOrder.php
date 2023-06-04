<?php
	$root_url = "../../";
	$src = "../";
	$page_title = 'Record Order list';
	include ("../includes/header.html");
	require_once("../mysql/mysqli.php");
?>
<h2>Record Order List</h2>
<?php
	$q = "SELECT * FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) WHERE approval.ApprovalStatusId = 2";
	$r = mysqli_query($dbc, $q) or die ('error');
	$num = mysqli_num_rows($r);
        if(mysqli_num_rows($r) > 0){
	echo '<table border="3" align="center" cellspacing="2" cellpadding="5">
                <tr>
					<td align="left"><b>Sales Order ID</b></td>
					<td align="left"><b>Customer Name</b></td>
					<td align="left"><b>Customer Address</b></td>
					<td align="left"><b>Contact Number</b></td>
					<td align="left"><b>Date Create</b></td>
					<td align="left"><b>Created By</b></td>
					<td align="left"><b>Approval ID</b></td>
					<td align="left"><b>Item ID</b></td>
					<td align="left"><b>Quantity</b></td>
                </tr>';
	
	$prev = "";
	while ($row = mysqli_fetch_assoc($r)){

		$isSame = $prev === $row['SalesOrderId'];
		
		$salesOrderId = $isSame ? "" : $row['SalesOrderId'];
		$customerName = $isSame ? "" : $row['CustomerName'];
		$customerAddress = $isSame ? "" : $row['CustomerAddress'];
		$contactNumber = $isSame ? "" : $row['ContactNumber'];
		$dateCreated = $isSame ? "" : $row['DateCreated'];
		$createdBy = $isSame ? "" : $row['CreatedBy'];
		$approvalId = $isSame ? "" : $row['ApprovalId'];

		echo '<tr>
			<td align="left">' .$salesOrderId. '</td>
			<td align="left">' .$customerName.'</td>
			<td align="left">' .$customerAddress. '</td>
			<td align="left">' .$contactNumber. '</td>
			<td align="left">' .$dateCreated. '</td>
			<td align="left">' .$createdBy. '</td>
			<td align="left">' .$approvalId. '</td>
			<td align="left">' . $row['ItemId'] . '</td>
			<td align="left">' . $row['Quantity'] . '</td>
		</tr>';

		$prev = $salesOrderId;
	}
	} 
	echo '</table>';

	mysqli_free_result($r); // Free up the resources.
	mysqli_close($dbc); // Close the database connection.

?>
<?php
include ('../includes/footer.html');
?>