<?php
if (postExists("modifyItemSubmit"))
{
    //VALIDATION starts here##################################################################################################
    $fields = 
    [
        //Min & max are inclusive
        new Field("itemSelect", "Item", Type::Select, null, null),
        new Field("itemQuantity", "item quantity", Type::Integer, 0, 9999999999)
    ];
    
    $errors = [];
    foreach($fields as $field) $field->validateError($errors);

    $postValues = array_map(fn($e) => $e->value, $fields);
    [$itemId, $itemQuantity] = $postValues;

    //Check if user keep the quantity same instead of update it
    if(empty($errors))
    {
        if ($row = $mysqli->execute_query("SELECT ItemQuantity FROM item WHERE ItemId = ?;", [$itemId]))
        {
            if ($row->fetch_assoc()["ItemQuantity"] == $itemQuantity)
            {
                $errors[] = "Please enter new quantity";
            }
        }
        else
        {
            echo "<p>Cannot read data!</p>";
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
        if ($mysqli->execute_query("UPDATE item SET ItemQuantity = ?, DateUpdated = NOW() WHERE ItemId = ? && SupplierId = ?;", [$itemQuantity, $itemId, $supplierId]))
        {
            echo "<p>Succesfully modify data</p>";
            echo "<p>New Data:-</p>";
            echo "<p>Item $itemId: $itemQuantity</p>";
        }
        else
        {
            echo "<p>Cannot update data!</p>";
        }
    }
}

if (postExists("addItemSubmit"))
{
    //VALIDATION starts here##################################################################################################
    $fields = 
    [
        //Min & max are inclusive
        new Field("itemSelect", "Item", Type::Select, null, null),
        new Field("itemQuantity", "item quantity", Type::Integer, 1, 9999999999)
    ];
    
    $errors = [];
    $fields[0]->validateError($errors);

    //Check for new maximum value for quantity
    if(empty($errors))
    {
        if ($row = $mysqli->execute_query("SELECT ItemQuantity FROM item WHERE ItemId = ?;", [$fields[0]->value]))
        {
            if ($oldQuantity= $row->fetch_assoc()["ItemQuantity"])
            {
                $fields[1]->max -= $oldQuantity;
            }
        }
        else
        {
            echo "<p>Cannot read data!</p>";
        }
    }
    $fields[1]->validateError($errors);
    
    $postValues = array_map(fn($e) => $e->value, $fields);
    [$itemId, $itemQuantity] = $postValues;
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
        if ($mysqli->execute_query("UPDATE item SET ItemQuantity = (ItemQuantity + ?), DateUpdated = NOW() WHERE ItemId = ? && SupplierId = ?;", [$itemQuantity, $itemId, $supplierId]))
        {
            echo "<p>Succesfully add quantity</p>";
            echo "<p>New Data:-</p>";
            echo "<p>Item $itemId: ".($oldQuantity + $itemQuantity)."</p>";
        }
        else
        {
            echo "<p>Cannot update data!</p>";
        }
    }
}
?>
<h2>Update Existing Item</h2>
<form action="" method="post" id="modifyItemForm">
    <h3>Modify Item</h3>
    <p>
        <legend>Item</legend>
        <select id="modifyItemSelect" name="itemSelect">
        <option value=''>Please Choose</option>
            <?php
            if ($itemsRow = $mysqli->execute_query("SELECT ItemId, ItemName, ItemQuantity FROM item WHERE SupplierId = ?;", [$supplierId]))
            {
                while ($row = $itemsRow->fetch_assoc())
                {
                    $itemId = $row["ItemId"];
                    $itemName = $row["ItemName"];
                    $itemQuantity = $row["ItemQuantity"];
    
                    $value = $itemId;
                    $quantity = $itemQuantity;
                    $innerHtml = "$itemId - $itemName";
    
                    echo "<option value='$value' quantity='$quantity' ".(GetPostIfExist("itemSelect")==$value?"selected":"").">$innerHtml</option>";
                }
            }
            else
            {
                echo "<p>Cannot read data!</p>";
            }
            ?>
        </select>
    </p>
    <p>
        <legend>Item Quantity</legend>
        <input type="number" id="modifyItemQuantity" name="itemQuantity" value="<?php echo getPostIfExist('itemQuantity')?>">
    </p>
    <br>
    <input type="submit" value="Confirm" name="modifyItemSubmit" id="modifyItemSubmit">
</form>
<form action="" method="post" id="updateItemForm">
    <h3>Add Item</h3>
    <p>
        <legend>Item</legend>
        <select id="addItemSelect" name="itemSelect">
        <option value=''>Please Choose</option>
            <?php
            if ($itemsRow = $mysqli->execute_query("SELECT * FROM item WHERE SupplierId = ?;", [$supplierId]))
            {
                while ($row = $itemsRow->fetch_assoc())
                {
                    $itemId = $row["ItemId"];
                    $itemName = $row["ItemName"];
                    $itemQuantity = $row["ItemQuantity"];
    
                    $value = $itemId;
                    $quantity = $itemQuantity;
                    $innerHtml = "$itemId - $itemName";
    
                    echo "<option value='$value' quantity='$quantity' ".(GetPostIfExist("itemSelect")==$value?"selected":"").">$innerHtml</option>";
                }
            }
            else
            {
                echo "<p>Cannot read data!</p>";
            }
            ?>
        </select>
    </p>
    <p>
        <legend>Item Quantity</legend>
        <input type="number" id="addItemQuantity" name="itemQuantity" value="<?php echo getPostIfExist('itemQuantity')?>">
    </p>
    <br>
    <input type="submit" value="Confirm" name="addItemSubmit" id="addItemSubmit">
</form>
<script>
    $("#modifyItemSelect").change(function()
    {
        const selectedOption = $(this).find(':selected');
        const quantity = selectedOption.attr('quantity');

        $("#modifyItemQuantity").val(quantity);
    });

</script>