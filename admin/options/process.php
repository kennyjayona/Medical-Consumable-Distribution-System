<?php

include 'conn.php';

// Function to add a new record
function addRecord($item_name, $item_quantity)
{
    global $conn;

    // Validate input data
    if (empty($item_name) || !is_numeric($item_quantity)) {
        echo "Invalid input data";
        return;
    }

    // Perform SQL insert operation
    $query = "INSERT INTO table_item (item_name, item_quantity) VALUES (:item_name, :item_quantity)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':item_quantity', $item_quantity);

    if ($stmt->execute()) {
        // Record added successfully
        header("Location: inventory.php"); // Redirect back to inventory.php
        exit();
    } else {
        // Error occurred
        echo "Error adding record";
    }
}

// Function to edit an existing record
function editRecord($id, $item_name, $item_quantity)
{
    global $conn;

    // Validate input data
    if (empty($item_name) || !is_numeric($item_quantity) || !is_numeric($id)) {
        echo "Invalid input data";
        return;
    }

    // Perform SQL update operation
    $query = "UPDATE table_item SET item_name = :item_name, item_quantity = :item_quantity WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':item_quantity', $item_quantity);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        // Record updated successfully
        header("Location: inventory.php"); 
        exit();
    } else {
        // Error occurred
        echo "Error editing record";
    }
}

// Process the form submissions
if (isset($_POST['add_item'])) {
    // Handle add item form submission
    $item_name = $_POST['item_name'];
    $item_quantity = $_POST['item_quantity'];

    addRecord($item_name, $item_quantity);
} elseif (isset($_POST['edit_item'])) {
    // Handle edit item form submission
    $edited_item_name = $_POST['edited_item_name'];
    $edited_item_quantity = $_POST['edited_item_quantity'];
    $edit_item_id = $_POST['edit_item_id'];

    editRecord($edit_item_id, $edited_item_name, $edited_item_quantity);
} else {
    // No valid form submission, redirect to supply.php
    header("Location: inventory.php");
    exit();
}

?>
