<?php
	$page_title = "Sales Performance";
	[$mysqli, $dbc] = Route::MYSQL_BOTH();
?>

<h2>Sales Performance by Month</h2>
<form action="" method="post">
	<p>Monthly Sales: 
    <?php
        $resultSet = $mysqli->query("SELECT DISTINCT MONTH(sales_order.DateCreated) AS month FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) WHERE approval.ApprovalStatusId = 2");
    ?>
        <select name="month">
        <?php
            while ($row = $resultSet->fetch_assoc()) {
                $month = $row["month"];
                echo "<option value='$month'>The <font color='blue'>$month</font> Month</option>";
            }
        ?>
        </select>

	<p><input type="submit" name="submit" value="Check" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
	// Check if the form has been submitted.
	if (isset($_POST['submitted'])) {
		
		$errors = array(); // Initialize error array.

		if (empty($_POST['month'])) {
			$errors[] = 'You forgot to select a month.';
		} else {
			$month = $_POST['month'];

			$query = "SELECT SUM(Quantity * ItemPrice) AS month_sales FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2 AND MONTH(sales_order.DateCreated) = '$month'";
			$result = mysqli_query($dbc, $query);
			$monthSales = mysqli_fetch_assoc($result);
			
			echo '<br/><table  border="3" align="center" cellspacing="2" cellpadding="15">
					<tr>
					<td align="center"><b>Total Sales by Month (RM)</b></td>
					</tr>
					<tr>
					<td align="center">' . $monthSales['month_sales'] . '</td>
					</tr>
				</table>';
		}
	}

?>