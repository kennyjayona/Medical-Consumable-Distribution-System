<?php
include 'conn.php'; // Include your database connection file

// Function to get status based on available quantity
function getStatus($availableQuantity) {
    if ($availableQuantity >= 100) {
        return '<span class="badge badge-success">Sufficient</span>';
    } elseif ($availableQuantity > 10 && $availableQuantity < 100) {
        return '<span class="badge badge-warning">Warning</span>';
    } elseif ($availableQuantity <= 10) {
        return '<span class="badge badge-danger">Out of Stock</span>';
    }
}

// Check if the request contains necessary data
if (isset($_POST['id']) && isset($_POST['newQuantity'])) {
    $id = $_POST['id'];
    $newQuantity = $_POST['newQuantity'];

    // Echo the new status badges for updating in the frontend
    echo getStatus($newQuantity);
} else {
    echo 'Invalid request';
}
?>
