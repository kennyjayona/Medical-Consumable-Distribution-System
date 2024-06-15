<?php
include 'conn.php'; // Include your database connection file
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
    exit(); // Make sure to exit after redirection
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate your form data (you should do more validation as needed)
    $brgy_name = $_POST['brgy_name'];
    // Add more fields as needed

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO table_brgy (brgy_name) VALUES (:brgy_name)");
    $stmt->bindParam(':brgy_name', $brgy_name, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Redirect to bgy.php after successful insertion
        header('location: bgy.php');
        exit(); // Make sure to exit after redirection
    } else {
        echo 'Error adding record';
    }
}
?>