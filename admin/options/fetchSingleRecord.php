<?php
include 'conn.php'; // Include your database connection file

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch updated record details based on $id
    $stmt = $conn->prepare("SELECT * FROM table_item WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the record exists
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the updated record details as JSON
        echo json_encode($row);
    } else {
        echo 'Record not found.';
    }
} else {
    echo 'Invalid request.';
}
?>
