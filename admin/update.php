<?php
include 'conn.php'; // Include your database connection file
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Check if the editField and other fields are provided
    if (isset($_POST['editField'], $_POST['editContactPerson'], $_POST['editContactNumber'])) {
        $editedValue = $_POST['editField'];
        $editedContactPerson = $_POST['editContactPerson'];
        $editedContactNumber = $_POST['editContactNumber'];

        // Update the record in the database
        $stmt = $conn->prepare("UPDATE table_brgy SET brgy_name = :editedValue WHERE id = :id");
        $stmt->bindParam(':editedValue', $editedValue, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Update contact_person and contact_number in request_table
            $stmtUpdateRequest = $conn->prepare("UPDATE request_table SET contact_person = :contactPerson, contact_number = :contactNumber WHERE brgy_id = :id");
            $stmtUpdateRequest->bindParam(':contactPerson', $editedContactPerson, PDO::PARAM_STR);
            $stmtUpdateRequest->bindParam(':contactNumber', $editedContactNumber, PDO::PARAM_STR);
            $stmtUpdateRequest->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmtUpdateRequest->execute()) {
                // Redirect to bgy.php after updating the record
                header('Location: bgy.php');
                exit; // Ensure that script execution stops after the redirect
            } else {
                echo 'Error updating record in request_table';
            }
        } else {
            echo 'Error updating record in table_brgy';
        }
    }
}
?>
