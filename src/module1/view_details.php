<?php
$page_title = 'Product Details';
require_once('mysqli.php'); // Connect to the db.
global $dbc;
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
$q = "SELECT * FROM item";
$r = mysqli_query($dbc, $q) or die('Error');
$num = mysqli_num_rows($r);

if ($num > 0) {
    echo '<table class="custom-table">
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Item Price</th>
            <th>Item Quantity</th>
            <th>Date Created</th>
            <th>Date Updated</th>
            <th>Supplier ID</th>
        </tr>';

    while ($row = mysqli_fetch_assoc($r)) {
        $itemid = $row['ItemId'];
        $itemName = $row['ItemName'];
        $itemDescription = $row['ItemDescription'];
        $itemPrice = $row['ItemPrice'];
        $itemQuantity = $row['ItemQuantity'];
        $DateCreated = $row['DateCreated'];
        $DateUpdated = $row['DateUpdated'];
        $Supplierid = $row['SupplierID'];

        echo '<tr>
            <td>' . $itemid . '</td>
            <td>' . $itemName . '</td>
            <td>' . $itemDescription . '</td>
            <td>' . $itemPrice . '</td>
            <td>' . $itemQuantity . '</td>
            <td>' . $DateCreated . '</td>
            <td>' . $DateUpdated . '</td>
            <td>' . $Supplierid . '</td>
        </tr>';
    }

    echo '</table>';
}

mysqli_free_result($r); // Free up the resources.
mysqli_close($dbc); // Close the database connection.
?>
<form method="post" action="logout_li.php">
    <input type="submit" name="logout" value="Logout">
</form>