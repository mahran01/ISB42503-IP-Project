<?php
$page_title = 'Product Details';
$dbc = Route::MYSQL_PROCEDURAL();

$agentId = Authenticator::Agent();
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
<h1>View Order Details</h1>
<h2>Agent: <?php echo $agentId ?></h2>
<?php
if (isset($_POST['logout'])) {
    // Perform logout actions here
    // For example, destroy session variables, clear cookies, etc.
    // Redirect the user to the login page or any other appropriate page
    header('Location: logout.php');
    exit();
}
?>

<?php
$q = "SELECT * FROM sales_order
    JOIN sales_order_line USING  (SalesOrderId)
    JOIN approval USING (ApprovalId)
    JOIN approval_status USING (ApprovalStatusId)
    JOIN item USING (ItemId)
    WHERE CreatedBy = $agentId";
$r = mysqli_query($dbc, $q) or die('Error');
$num = mysqli_num_rows($r);

if ($num > 0) {
    echo '<table class="custom-table">
        <tr>
            <th>Sales Order Id</th>
            <th>Customer Name</th>
            <th>Customer Address</th>
            <th>Contact Number</th>
            <th>Item Name</th>
            <th>Item Quantity</th>
            <th>Approval Status</th>
        </tr>';

    while ($row = mysqli_fetch_assoc($r)) {
        $SalesOrderId = $row['SalesOrderId'];
        $CustomerName = $row['CustomerName'];
        $customerAddress = $row['CustomerAddress'];
        $ContactNumber = $row['ContactNumber'];
        $ItemName = $row['ItemName'];
        $Quantity = $row['Quantity'];
        $ApprovalStatus = $row['ApprovalStatusName'];

        echo '<tr>
            <td>' . $SalesOrderId . '</td>
            <td>' . $CustomerName . '</td>
            <td>' . $customerAddress . '</td>
            <td>' . $ContactNumber . '</td>
            <td>' . $ItemName. '</td>
            <td>' . $Quantity. '</td>
            <td>' . $ApprovalStatus . '</td>
        </tr>';
    }

    echo '</table>';
}

mysqli_free_result($r); // Free up the resources.
mysqli_close($dbc); // Close the database connection.
?>