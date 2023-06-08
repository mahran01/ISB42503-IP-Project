<?php
if (isset($_POST['url'])) $_SESSION['url'] = $_POST['url'];
if (postExists('updateApprovalSubmit'))
{
    //VALIDATION starts here##################################################################################################
    $approvalSelect = new Field("approvalSelect[]", "ApprovalSelect", Type::Select, null, null);
    $order = $_POST['order'];
    $itemOnHand = $_POST['quantityOnHand'];

    $errors = [];
    $approvalSelect->validateError($errors);

    $declined = array_filter($approvalSelect->value, fn($v) => $v == 3);
    $approved = array_filter($approvalSelect->value, fn($v) => $v == 2);
    $orderApproved = array_intersect_key($order, $approved);

    $itemTotals = array_reduce($orderApproved, function ($c, $items) {
        foreach ($items as $id => $q) {
            $c[$id] = isset($c[$id]) ? $c[$id] + $q : $q;
        }
        return $c;
    }, []);

    foreach ($itemTotals as $id => $q)
    {
        if ($itemTotals[$id] > $itemOnHand[$id])
        {
            $errors[] = "Item $id on hand ($itemOnHand[$id]) is insufficient for amount requested ($itemTotals[$id])";
        }
    }
    //VALIDATION ends here ###################################################################################################

    if (!empty($errors))
    {
        echo "<h1>Error!</h1>";
    	echo "<p>The following error(s) occurred:</p>";
        echo "<ul>";
        foreach ($errors as $error) echo "<li>$error</li>";
        echo "</ul>";
    }
    else
    {
        $mysqli->begin_transaction();
        try
        {
            foreach ($approved as $orderId => $approval)
            {
                $mysqli->execute_query('UPDATE approval JOIN sales_order USING (ApprovalId) SET ApprovalStatusId = 2, DateUpdated = NOW() WHERE SalesOrderId = ?;', [$orderId]);
                
                foreach ($orderApproved[$orderId] as $itemId => $quantity)
                {
                    $mysqli->execute_query('UPDATE item SET ItemQuantity = (ItemQuantity - ?), DateUpdated = NOW() WHERE ItemId = ?', [$quantity, $itemId]);
                }
            }
            foreach ($declined as $orderId => $approval)
            {
                $mysqli->execute_query('UPDATE approval JOIN sales_order USING (ApprovalId) SET ApprovalStatusId = 3, DateUpdated = NOW() WHERE SalesOrderId = ?;', [$orderId]);
            }

            //Commit transaction
            $mysqli->commit();
            
            // Send an email, if desired.
            
            // Print a message.
            echo '<h1 id="mainhead">Thank you!</h1>
            <p>The Order is being updated.</p><p><br /></p>';	
        
            // Include the footer and quit the script (to not show the form).
            exit();
        }
        catch (Exception $e)
        {
            // Rollback the transaction
            $mysqli->rollback();

            //@TODO handle the error, e.g., delete the inserted row
            echo '<h1 id="mainhead">System Error</h1>
            <p class="error">The item could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
            // include ('./includes/footer.php'); 
            exit();
        }
    }
}
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
<h2>Pending approval</h2>
<form action="" method="post" id="modifyItemForm">
    <h3>Order Data</h3>
    <table style="width: 40vw;" class="custom-table">
        <?php
        if (($rows = $mysqli->execute_query(
            'SELECT SalesOrderId OrderId, CreatedBy AgentId, UserName AgentName, so.DateCreated, ItemId, ItemName, sol.Quantity QuantityRequested, i.ItemQuantity QuantityOnHand, ApprovalStatusName
            FROM sales_order so 
            JOIN sales_order_line sol USING (SalesOrderId) 
            JOIN role_Agent ra ON so.CreatedBy = ra.AgentId
            JOIN user_account ua ON ra.AgentId = ua.UserId
            JOIN item i USING (ItemId)
            JOIN approval USING (ApprovalId)
            JOIN approval_status USING (ApprovalStatusId)
            WHERE ApprovalStatusId = 1
            AND ra.SupplierId = ?
        ', [$supplierId])) && $rows->num_rows > 0) : ?>
        <tr>
            <th>Order Id</th>
            <th>Agent</th>
            <th>Date Created</th>
            <th>Item</th>
            <th>Quantity</br>Requested</th>
            <th>Quantity</br>On Hand</th>
            <th>Approval</th>
        </tr>
        <?php
            $prev = '';
            while ($row = $rows->fetch_assoc())
            {
                $isSame = $prev == $row['OrderId'];
                
                $orderId = $row['OrderId'];
                $agent = $row['AgentId']."-".$row['AgentName'];
                $dateCreated = substr($row['DateCreated'], 0, 10);
                $item = $row['ItemId']."-".$row['ItemName'];
                $quantityRequested = $row['QuantityRequested'];
                $quantityOnHand = $row['QuantityOnHand'];
                $approval = $row['ApprovalStatusName'];

                $itemId = $row['ItemId'];
                echo "<input type='hidden' name='order[$orderId][$itemId]' value='$quantityRequested'/>";
                echo "<input type='hidden' name='quantityOnHand[$itemId]' value='$quantityOnHand'/>";
                
                if ($isSame)
                {
                    $orderId = '';
                    $agent = '';
                    $dateCreated = '';
                    $approval = '';
                }
                echo "<tr>";
                {
                    
                    echo "<th>$orderId</th>";
                    echo "<th>$agent</th>";
                    echo "<th>$dateCreated</th>";
                    echo "<th>$item</th>";
                    echo "<th>$quantityRequested</th>";
                    echo "<th>$quantityOnHand</th>";
                    echo "<th>";
                    if (!$isSame){
                        echo "<select name='approvalSelect[$orderId]' orderId='$orderId'>";
                        if ($approvalRows = $mysqli->execute_query('SELECT * FROM approval_status'))
                        {
                            while ($approvalRow = $approvalRows->fetch_assoc())
                            {
                                $value = $approvalRow['ApprovalStatusId'];
                                $html = $approvalRow['ApprovalStatusName'];
                                echo "<option value='$value'>{$html}</option>";
                            }
                        }
                        echo "</select>";
                    }
                    echo "</th>";
                }
                echo "</tr>";

                $prev = $orderId;
            }
        else :
        //@TODO handle the error, e.g., delete the inserted row
        echo '<h3 id="mainhead">No pending approval...</h3>';
        exit(); 
        endif ?>
    </table>
    <br/>
    <input type="submit" value="Update" name="updateApprovalSubmit" id="updateApprovalSubmit">
</form>