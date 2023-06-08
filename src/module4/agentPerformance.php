<?php
	$page_title = "Agent Performance";
	[$mysqli, $dbc] = Route::MYSQL_BOTH();
?>


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

		echo '<br/><table  border="3" align="center" cellspacing="2" cellpadding="15">
				<tr>
				<td align="center"><b>Number of Product Sold</b></td>
				<td align="center"><b>Total Sales (RM)</b></td>
				</tr>
				<tr>
				<td align="center">' . $result_quantity['sum_item'] . '</td>
				<td align="center">' . $result_sales['total_sales'] . '</td>
				</tr>
			</table>';
	}

?>