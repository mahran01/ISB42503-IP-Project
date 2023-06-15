<?php
	$page_title = 'Reciept Order';
	[$mysqli, $dbc] = Route::MYSQL_BOTH();
    $supplierId = Authenticator::Supplier();
?>
<h2>Reciept Order</h2>
<form action="" method="post">
	<p>Sales Order Id: 
	<?php
		$resultSet= $mysqli->query("SELECT DISTINCT SalesOrderId FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2 AND approval.ApprovedBy = $supplierId");
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
        </p>
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
        echo "There is no sales order yet!!";
    } else {
        $salesOrderId = ($_POST['salesOrderId']);

    $q = "SELECT * FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2 AND sales_order.SalesOrderId = $salesOrderId AND approval.ApprovedBy = $supplierId";
    $r = mysqli_query($dbc, $q) or die ('error');
	$num = mysqli_num_rows($r);

    if ($num > 0) {
        $row = mysqli_fetch_assoc($r);

        echo'<h3>RECEIPT ORDER</h3>';
        echo '<b>Sales Order Id:</b> ' . $row['SalesOrderId'];
        echo '<br/><b>Created By:</b> ' . $row['CreatedBy'];
        echo '<br/>--------------------------------';
        echo '<br/><b>Customer Name:</b> ' . $row['CustomerName'];
        echo '<br/><b>Customer Number:</b> ' . $row['ContactNumber'];
        echo '<br/><b>Date:</b> ' . $row['DateCreated'];
        echo '<br/>--------------------------------';
        echo '<table border="0" cellspacing="2" cellpadding="5" class="custom-table">
                <tr>
					<td align="left"><b>Item ID</b></td>
					<td align="left"><b>Item Name</b></td>
					<td align="left"><b>Quantity</b></td>
                    <td align="left"><b>Price</b></td>
                </tr>';
        do {
            echo '<tr>
                <td align="left">' . $row['ItemId'] . '</td>
                <td align="left">' . $row['ItemName'] . '</td>
                <td align="left">' . $row['Quantity'] . '</td>
                <td align="left">RM' . ($row['Quantity']*$row['ItemPrice']). '</td>
                </tr>';

        } while ($row = mysqli_fetch_assoc($r));
        echo '</table>';
	} 

    $query = mysqli_query($dbc,"SELECT SUM(Quantity * ItemPrice) AS total_price FROM sales_order INNER JOIN sales_order_line USING (SalesOrderId) INNER JOIN approval USING (ApprovalId) INNER JOIN item USING (ItemId) WHERE approval.ApprovalStatusId = 2 AND sales_order.SalesOrderId = $salesOrderId");
	$total_price = mysqli_fetch_array($query);
    echo '<br/>--------------------------------';
	echo '<br/><b>TOTAL PRICE:</b> RM'. $total_price['total_price'];
    echo '<br/>--------------------------------';
    }
}

?>