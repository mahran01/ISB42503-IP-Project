<?php
	$page_title = "Agent Performance";
	[$mysqli, $dbc] = Route::MYSQL_BOTH();
    $supplierId = Authenticator::Supplier();
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
<h2>Agent Performance</h2>
<form action="" method="post">
	<p>Agent: 
	<?php
		$resultSet= $mysqli->query("SELECT DISTINCT CreatedBy FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) WHERE approval.ApprovalStatusId = 2");
	?>
		<select name ="agent">
		<!-- <option value="" >Select Agent</option> -->
			<?php
				while ($row = $resultSet->fetch_assoc())
				{   
					$agent= $row["CreatedBy"];
				echo "<option value='$agent'>$agent</option>";
				}
			?>
		</select>
	<p><input type="submit" name="submit" value="Check" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<?php
	// Check if the form has been submitted.
	if (isset($_POST['submitted'])) {
		
		/*function escape_data ($data) {
				
			if (ini_get('magic_quotes_gpc')) {
					$data = stripslashes($data);
			}
			return mysql_real_escape_string(trim($data));
		} // End of function.*/

		$errors = array(); // Initialize error array.

		if (empty($_POST['agent'])) {
			$errors[] = 'You forgot to select an agent.';
		} else {
			$agent = ($_POST['agent']);
		}

		$query = mysqli_query($dbc,"SELECT SUM(Quantity) AS sum_item FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) WHERE approval.ApprovalStatusId = 2 AND sales_order.CreatedBy = $agent");
		$result_quantity = mysqli_fetch_array($query);
		// echo $result_quantity['sum_item'] . "<br>";

		$query = mysqli_query($dbc,"SELECT SUM(Quantity * ItemPrice) AS total_sales FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2 AND sales_order.CreatedBy = $agent");
		$result_sales = mysqli_fetch_array($query);
		// echo $result_sales['total_sales'];

		echo "<br/><b>Agent:</b> $agent <br/>";
		echo '<br/><table  border="3" align="center" cellspacing="2" cellpadding="15" class="custom-table">
				<tr>
				<td align="center"><b>Number of Product Sold</b></td>
				<td align="center"><b>Total Sales (RM)</b></td>
				</tr>
				<tr>
				<td align="center">' . $result_quantity['sum_item'] . '</td>
				<td align="center">' . $result_sales['total_sales'] . '</td>
				</tr>
			</table>';

			$q = "SELECT * FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2 AND sales_order.CreatedBy = $agent";
			$r = mysqli_query($dbc, $q) or die ('error');
			$num = mysqli_num_rows($r);
				if(mysqli_num_rows($r) > 0){

			echo '<br/><b>Customers list:</b><br/>';
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
	}

?>