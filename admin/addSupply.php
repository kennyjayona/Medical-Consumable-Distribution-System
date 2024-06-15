<?php
include '../admin/conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

// Check if REQUEST_METHOD is set in the $_SERVER array
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate your form data
    $item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
    $item_quantity = isset($_POST['item_quantity']) ? $_POST['item_quantity'] : '';
    $exp_date = isset($_POST['exp_date']) ? $_POST['exp_date'] : '';
    $batch_number = isset($_POST['batch_number']) ? substr($_POST['batch_number'], 0, 200) : ''; // Truncate to 200 characters

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO table_item (item_name, item_quantity, added_date, exp_date, batch_number) VALUES (?, ?, NOW(), ?, ?)");
    $stmt->bindParam(1, $item_name, PDO::PARAM_STR);
    $stmt->bindParam(2, $item_quantity, PDO::PARAM_INT);
    $stmt->bindParam(3, $exp_date, PDO::PARAM_STR);
    $stmt->bindParam(4, $batch_number, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Item inserted successfully, redirect to supply.php
        header('location: supply.php');
        exit(); // Make sure to exit after the redirect
    } else {
        echo 'Error adding record';
    }
} else {
    echo 'Invalid request method'; // You can customize this error message as needed
}
?>
