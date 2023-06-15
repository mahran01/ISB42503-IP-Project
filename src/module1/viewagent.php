<?php
$page_title = 'Product Details';
$dbc = Route::MYSQL_PROCEDURAL();
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
<h1>View Item Details</h1>
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
$q = "SELECT * FROM role_agent WHERE role_agent.SupplierId = $supplierId ";
$r = mysqli_query($dbc, $q) or die('Error');
$num = mysqli_num_rows($r);

if ($num > 0) {
    echo '<table class="custom-table">
        <tr>
            <th>AGENT</th>
        </tr>';

    while ($row = mysqli_fetch_assoc($r)) {
        $agent = $row['AgentId'];

        echo '<tr>
            <td>' . $agent . '</td>
        </tr>';
    }

    echo '</table>';
}

mysqli_free_result($r); // Free up the resources.
mysqli_close($dbc); // Close the database connection.
?>