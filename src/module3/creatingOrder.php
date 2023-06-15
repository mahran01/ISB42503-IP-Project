<?php
if (isset($_SESSION['url']) && $_SESSION['url'] != '/createOrder') unset($_SESSION['customerDetail']);

if (postExists("customerDetailSubmit"))
{
    //VALIDATION starts here##################################################################################################
    //General error
    $fields = 
    [
        //Min & max are inclusive
        new Field("customerName", "customer Name", Type::String, 1, 50),
        new Field("customerAddress", "address", Type::String, 1, 255),
        new Field("contactNumber", "contact no.", Type::String, 1, 15)
    ];
    $errors = [];
    foreach($fields as $field) $field->validateError($errors);
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
        // foreach($fields as $field)
        // {
        //     $_SESSION[$field->name] = $field->value;
        // }
        $result = array_reduce($fields, fn($carry, $field) => [$field->name => $field->value] + $carry, []);
        $_SESSION["customerDetail"] = $result;
    }
}
if (postExists("cancelAddItemSubmit"))
{
    //VALIDATION starts here##################################################################################################
    //Techincal error
    if (!sessionExists("customerDetail"))
    {
        echo "<h1>Unknown error</h1>";
        exit();
    }
    //VALIDATION ends here ###################################################################################################
    unset($_SESSION['customerDetail']);
}
if (postExists("addItemSubmit"))
{
    //VALIDATION starts here##################################################################################################
    //Techincal error
    if (!sessionExists("customerDetail"))
    {
        echo "<h1>Unknown error</h1>";
        unset($_SESSION['customerDetail']);
        exit();
    }
    // General error
    $fields = [];
    $itemNumber = (count($_POST) - 1) / 2;
    for ($i = 1; $i <= $itemNumber; $i++)
    {
        $fields[] = new Field("itemSelect-{$i}", "item number {$i}", Type::Select, null, null);
        $fields[] = new Field("itemQuantity-{$i}", "item quantity {$i}", Type::Integer, 1, 9999999999);
    }
    $errors = [];
    foreach($fields as $field) $field->validateError($errors);
    [
        'customerName' => $customerName,
        'customerAddress'=> $customerAddress,
        'contactNumber' => $contactNumber
    ] = getSessionIfExist('customerDetail');

    $values = array_map(fn($field) => $field->value, $fields);

    $items = [];
    for ($i = 0; $i < count($values);)
    {
        $items[$values[$i++]] = $values[$i++];
    }
    // //VALIDATION ends here ###################################################################################################

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
            $mysqli->execute_query('INSERT INTO approval VALUE();');
            $approvalId = $mysqli->insert_id;
            $mysqli->execute_query(
                'INSERT INTO sales_order (CustomerName, CustomerAddress, ContactNumber, CreatedBy, ApprovalId)
                VALUES (?, ?, ?, ?, ?);', [$customerName, $customerAddress, $contactNumber, $agentId, $approvalId]
            );
            $salesOrderId = $mysqli->insert_id;
            foreach ($items as $itemId => $itemQuantity)
            {
                $mysqli->execute_query(
                    'INSERT INTO sales_order_line (SalesOrderId, ItemId, Quantity)
                    VALUES (?, ?, ?);', [$salesOrderId, $itemId, $itemQuantity]
                );
            }
            //Commit transaction
            $mysqli->commit();

            //@TODO print success
            unset($_SESSION['customerDetail']);

            // Send an email, if desired.
            
            // Print a message.
            echo '<h1 id="mainhead">Thank you!</h1>
            <p>The item is now added. </p><p><br /></p>';	

            exit();
        }
        catch(Exception $e)
        {
            // Rollback the transaction
            $mysqli->rollback();
            unset($_SESSION['customerDetail']);

            //@TODO handle the error, e.g., delete the inserted row
            echo '<h1 id="mainhead">System Error</h1>
            <p class="error">The item could not be added due to database error. We apologize for any inconvenience.</p>
            <font color="red">Error : '.$e.'</font>'; // Public message.

            exit();
        }
    }
}
?>

<!-- Print Customer Detail Form -->
<?php if (!sessionExists("customerDetail")): ?>
<h2>Create New Order</h2>
<form action="" method="post" id="customerDetailForm">
    <h3>Customer Detail</h3>
    <p>
        <legend>Customer Name</legend>
        <input type="text" name="customerName" value="<?php echo getPostIfExist('customerName')?>">
    </p>
    <p>
        <legend>Address</legend>
        <input type="text" name="customerAddress" value="<?php echo getPostIfExist('customerAddress')?>">
    </p>
    <p>
        <legend>Contact No.</legend>
        <input type="text" name="contactNumber" value="<?php echo getPostIfExist('contactNumber')?>">
    </p>
    <br>
    <input type="submit" value="Confirm" name="customerDetailSubmit" id="customerDetailSubmit">
</form>

<!-- Print Item To Add Form -->
<?php else: ?>
    <?php if ($itemsRow = $mysqli->execute_query("SELECT ItemId, ItemName, ItemQuantity FROM item JOIN role_agent ON item.SupplierId = role_agent.SupplierId WHERE AgentID = ?;", [$agentId])): ?>
        <?php $itemsData = $itemsRow->fetch_all(MYSQLI_ASSOC) ?>
        <h2>Item To Add</h2>
        <form action="" method="post" id="addItemForm">
            <div id="itemsContainer">
                <style>
                    #addItemCard:hover {
                        cursor: pointer;
                    }
                    .icon-plus {
                        position:absolute;
                        background-color:black;
                        display:block;
                        height:40px;
                        width:40px;
                        border-radius:50%;
                        top:calc(50% - 20px);
                        left:calc(50% - 20px);
                    }
                    .icon-plus:after {
                        width: 22px;
                        height: 6px;
                        left: 9px;
                        top: 17px;
                    }
                    .icon-plus:before {
                        width: 6px;
                        height: 22px;
                        left: 17px;
                        top: 9px;
                    }
                    .icon-plus:after, .icon-plus:before {
                        content: '';
                        position: absolute;
                        background: #FFF;
                        border-radius: 2px;
                    }
                </style>
                <fieldset class="border rounded p-2" id="addItemCard" style="background-color: whitesmoke; height:125px; width:200px; min-inline-size:unset; position:relative;">
                    <label>Add Item</label>
                    <div class="icon-plus"></div>
                </fieldset>
            </div>
            <br>
            <input type="submit" value="Back" name="cancelAddItemSubmit" id="cancelAddItemSubmit">
            <input type="submit" value="Confirm" name="addItemSubmit" id="addItemSubmit">
        </form>

        <script>
            var data = [];
            var data = [ ...<?php echo json_encode($itemsData)?>];
            const MAX = data.length;
            var count = 0;
            const currSelect = {};
            
            const init = () => {
                addItemComponent();
            }

            $('#addItemCard').click(() => {
                if (count < MAX)
                {
                    addItemComponent();
                }
                if (count >= MAX)
                {
                    $('#addItemCard').css('display', 'none');
                }
            });
            
            const addItemComponent = () => {

                let i = count + 1;

                var $fieldset = $('<fieldset>',{
                'id': `itemCard-${i}`,
                'number': i,
                'style': 'width: 200px; min-inline-size: unset; position: relative; '
                });

                $('<legend>', {
                'id': `legend-${i}`,
                'text': `Item ${i}`
                }).appendTo($fieldset);

                //Cross button to remove an item
                var $cross = $('<div>',{
                'style': 'border-radius: 50%; display: block; position: absolute; width: 20px; height: 20px; right: 15px;'
                }).mouseenter(function() {
                    $cross.css({
                        'cursor': 'pointer',
                        'background-color': 'black'
                    });
                    $cross.children().css('background-color', 'white');
                }).mouseleave(function() {
                    $cross.css({
                        'cursor': 'unset',
                        'background-color': 'white'
                    });
                    $cross.children().css('background-color', 'black');
                }).click(function() {
                    if (count == Math.abs(1)) {
                        alert("Cannot delete last item");
                        return;
                    }
                    let i = $fieldset.attr('number');
                    $(`#itemSelect-${i}`).val('').change();

                    for (let j = Number(i) + 1; j < count + 1; j++)
                    {
                        let k = j - 1;
                        currSelect[k] = currSelect[j];
                        delete currSelect[j];
                        $(`#itemCard-${j}`).attr('number', k).attr('id', `itemCard-${k}`);
                        $(`#legend-${j}`).html(`Item ${k}`).attr('id', `legend-${k}`);
                        $(`#itemSelect-${j}`).attr('name', `itemSelect-${k}`).attr('id', `itemSelect-${k}`);
                        $(`#itemQuantity-${j}`).attr('name', `itemQuantity-${k}`).attr('id', `itemQuantity-${k}`);
                    }
                    $fieldset.remove();
                    count--;
                    if (count < MAX)
                    {
                        $('#addItemCard').css('display', 'block');
                    }
                }).appendTo($fieldset);

                $('<div>',{
                'style': 'background-color: black; display: block; position: absolute; width: 14px; height: 4px; top: 8px; right: 3px; transform:rotate(45deg)'
                }).appendTo($cross);
                $('<div>',{
                'style': 'background-color: black; display: block; position: absolute; width: 4px; height: 14px; top: 3px; right: 8px; transform:rotate(45deg)'
                }).appendTo($cross);

                var $firstP = $('<p>').appendTo($fieldset);
                
                $('<legend>', {
                'text': 'Item'
                }).appendTo($firstP);

                //Add Item Select for selecting item with function where when any item 
                //is selected here, that item will not be shown in other selection
                var $itemSelect = $('<select>', {
                'id': `itemSelect-${i}`,
                'name': `itemSelect-${i}`,
                }).appendTo($($firstP));
                
                //Adding default option
                $('<option>', {
                'value': '',
                'text': 'Please Choose'
                }).appendTo($itemSelect);
                
                //Adding other option START HERE ////////////////////////////////////////////////////////////////////////////////////
                //If any option is already selected in other field it would not be shown
                const selectedId = $.map(currSelect, ({value}) => value).reduce(function(acc, item) {
                    acc[item] = true;
                    return acc;
                }, {});

                data.forEach(({ItemId, ItemName, ItemQuantity}) => {
                    $('<option>', {
                    'value': ItemId,
                    'text': `${ItemId} - ${ItemName}`})
                    .css('display', !(ItemId in selectedId) ? 'block' : 'none')
                    .appendTo($itemSelect);
                });

                //Add current data into current Selection for future use
                var selected = $itemSelect.find(':selected');
                var value = selected.attr('value');
                var html = selected.html();

                currSelect[i] = {'selected':selected, 'value':value, 'html':html};
                //Adding other option ends here ////////////////////////////////////////////////////////////////////////////////////
                
                $itemSelect.change(function()
                {
                    let i = $fieldset.attr('number');
                    selected = $(this).find(':selected');
                    value = selected.attr('value');
                    html = selected.html();

                    for (let j = 1; j < count + 1; j++)
                    {
                        if (i == j) continue;

                        const oldValue = currSelect[i]['value'];
                        if (value != '')
                        {
                            $(`#itemSelect-${j} option:contains(${value})`).css('display', 'none');
                        }
                        if (oldValue != '')
                        {
                            $(`#itemSelect-${j} option:contains(${oldValue})`).css('display', 'block');
                        }
                    }
                    currSelect[i] = {'selected':selected, 'value':value, 'html':html};
                })
                var $secondP = $('<p>').appendTo($fieldset);

                $('<legend>', {
                'text': 'Quantity'
                }).appendTo($($secondP));

                $('<input>', {
                'type': 'number',
                'id': `itemQuantity-${i}`,
                'name': `itemQuantity-${i}`,
                'value': ''
                }).appendTo($($secondP));

                $fieldset.insertBefore('#itemsContainer > fieldset:last');

                //Increase counter
                count++;
            }
            
            init();
        </script>
    <?php else: ?>
        <p>Cannot read data!</p>
    <?php endif ?>
<?php endif ?>