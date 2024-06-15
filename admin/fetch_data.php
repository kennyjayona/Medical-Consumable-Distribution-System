<?php
include 'conn.php'; // Include your database connection file
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch data based on $id
    $stmt = $conn->prepare("SELECT * FROM table_item WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return HTML content for the modal body
        echo '<form id="editForm" action="update_supply.php" method="post">';
        echo '<div class="form-group">';
        echo '<label for="editName">Edit Item Name:</label>';
        echo '<input type="text" class="form-control" id="editName" name="editField" value="' . $row['item_name'] . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="editQuantity">Edit Item Quantity:</label>';
        echo '<input type="text" class="form-control" id="editQuantity" name="editQuantity" value="' . $row['item_quantity'] . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="editBatch_number">Edit Batch Number:</label>';
        echo '<input type="text" class="form-control" id="editBatch_number" name="editBatch_number" value="' . $row['batch_number'] . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="editExp_date">Edit Expiration Date:</label>';
        echo '<input type="text" class="form-control" id="editExp_date" name="editExp_date" value="' . $row['exp_date'] . '">';
        echo '</div>';
        echo '<input type="hidden" name="id" value="' . $id . '">';
        echo '<button type="submit" class="btn btn-block btn-info btn-sm" id="editFormButton">
                <i class="fas fa-save"></i> Save Changes
            </button>';
        echo '</form>';
        exit; // Stop further execution
    } else {
        echo 'Record not found.';
        exit; // Stop further execution
    }
}
?>