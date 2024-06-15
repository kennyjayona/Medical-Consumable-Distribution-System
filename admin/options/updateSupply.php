<?php
include 'conn.php'; // Include your database connection file
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
// Check if the form data is submitted for adding a new record
if (isset($_POST['addRecord'])) {
    $itemName = $_POST['item_name'];
    $itemQuantity = $_POST['item_quantity'];

    // Insert the new record into the database
    $stmt = $conn->prepare("INSERT INTO table_item (item_name, item_quantity) VALUES (:itemName, :itemQuantity)");
    $stmt->bindParam(':itemName', $itemName, PDO::PARAM_STR);
    $stmt->bindParam(':itemQuantity', $itemQuantity, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo 'Record added successfully';
    } else {
        echo 'Error adding record';
    }
} elseif (isset($_POST['id'])) {
    // Check if the editFields are provided for updating an existing record
    if (isset($_POST['editField']) && isset($_POST['editQuantity'])) {
        $id = $_POST['id'];
        $editedName = $_POST['editField'];
        $editedQuantity = $_POST['editQuantity'];

        // Update the record in the database
        $stmt = $conn->prepare("UPDATE table_item SET item_name = :editedName, item_quantity = :editedQuantity, added_date = NOW() WHERE id = :id");
        $stmt->bindParam(':editedName', $editedName, PDO::PARAM_STR);
        $stmt->bindParam(':editedQuantity', $editedQuantity, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
                // Fetch the updated record details
        $stmt = $conn->prepare("SELECT * FROM table_item WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $updatedRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            echo 'Record updated successfully';
        } else {
            echo 'Error updating record';
        }
    } else {
        // Fetch record details based on $id
        $id = $_POST['id'];
        $stmt = $conn->prepare("SELECT * FROM table_item WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the record exists
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Display the form with fetched record details
            echo '<form id="editForm" action="" method="post">';
            echo '<div class="form-group">';
            echo '<label for="editName">Edit Item Name:</label>';
            echo '<input type="text" class="form-control" id="editName" name="editField" value="' . $row['item_name'] . '">';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="editQuantity">Edit Item Quantity:</label>';
            echo '<input type="text" class="form-control" id="editQuantity" name="editQuantity" value="' . $row['item_quantity'] . '">';
            echo '</div>';
            echo '<input type="hidden" name="id" value="' . $id . '">';
            echo '<button type="submit" class="btn btn-block btn-info btn-sm" id="editFormButton">
                    <i class="fas fa-save"></i> Save Changes
                </button>';
            echo '</form>';
        } else {
            echo 'Record not found.';
        }
    }
}


?>
