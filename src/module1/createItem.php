<?php
$mysqli = Route::MYSQL();
$supplierId = Authenticator::Supplier();
if (postExists("createNewItemSubmit"))
{
    //VALIDATION starts here##################################################################################################
    //General error
    $fields = 
    [
        //Min & max are inclusive
        new Field("itemCode", "item code", Type::Integer, 1, 9999999999),
        new Field("itemName", "item name", Type::String, 1, 50),
        new Field("itemDesc", "item desc", Type::String, 1, 255),
        new Field("itemPrice", "item price", Type::Currency, 0, 9999999999.99),
        new Field("itemQuantity", "item quantity", Type::String, 0, 9999999999),
    ];
    
    $errors = [];
    foreach($fields as $field) $field->validateError($errors);

    $postValues = array_map(fn($field) => $field->value, $fields);
    [$itemId, $itemName, $itemDesc, $itemPrice, $itemQuantity] = $postValues;

    //Data error
    if (empty($errors))
    {
        if ($row = $mysqli->execute_query("SELECT itemId FROM item WHERE itemId= ?;", [$itemId]))
        {
            if ($row->num_rows != 0)
            {
                $errors[] = "The item has already been registered.";
            }
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
        if ($mysqli->execute_query(
            "INSERT INTO item (ItemId, ItemName, ItemDescription, ItemPrice, ItemQuantity, DateCreated, DateUpdated, SupplierId) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW(), ?)", [$itemId, $itemName, $itemDesc, $itemPrice, $itemQuantity, $supplierId]
        ))
        {
            // Send an email, if desired.
            
            // Print a message.
            echo '<h1 id="mainhead">Thank you!</h1>
            <p>The item is now added. </p><p><br /></p>';	
        
            // Include the footer and quit the script (to not show the form).
            // include ('./a_includes/footer.php'); 
            exit();
        }
        else
        {
            echo '<h1 id="mainhead">System Error</h1>
            <p class="error">The item could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
            // include ('./includes/footer.php'); 
            exit();
        }
    }
}
?>
<h2>Create New Item</h2>
<form action="" method="post" id="createNewItemForm">
    <h3>Item</h3>
    <p>
        <legend>Item code</legend>
        <input type="text" name="itemCode" value="<?php echo getPostIfExist('itemCode')?>">
    </p>
    <p>
        <legend>Item Name</legend>
        <input type="text" name="itemName" value="<?php echo getPostIfExist('itemName')?>">
    </p>
    <p>
        <legend>Item Description</legend>
        <input type="text" name="itemDesc" value="<?php echo getPostIfExist('itemDesc')?>">
    </p>
    <p>
        <legend>Item Price</legend>
        <input type="text"  name="itemPrice" value="<?php echo getPostIfExist('itemPrice')?>" DISABLED-onkeypress="return CheckNumeric()" DISABLED-onkeyup="FormatCurrency(this)">
    </p>
    <p>
        <legend>Item Quantity</legend>
        <input type="number" name="itemQuantity" value="<?php echo getPostIfExist('itemQuantity')?>">
    </p>
    <br>
    <input type="submit" value="Confirm" name="createNewItemSubmit" id="createNewItemSubmit">
</form>
<script >
    function FormatCurrency(ctrl) {
        //Check if arrow keys are pressed - we want to allow navigation around textbox using arrow keys
        if (event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40) {
            return;
        }

        var val = ctrl.value;

        val = val.replace(/,/g, "")
        ctrl.value = "";
        val += '';
        x = val.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';

        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }

        ctrl.value = x1 + x2;
    }

    function CheckNumeric() {
        return event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode == 46;
    }
  </script>
