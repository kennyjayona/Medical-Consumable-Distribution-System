<?php
include 'conn.php'; // Include your database connection file
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch data based on $id, including data from request_table
    $stmt = $conn->prepare("SELECT b.*, r.contact_person, r.contact_number
                            FROM table_brgy b
                            LEFT JOIN request_table r ON b.id = r.brgy_id
                            WHERE b.id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Display the form with fetched record details
        echo '<form id="editForm" action="update.php" method="post">';
        echo '<div class="form-group">
                <label for="editField">Edit Field:</label>
                <input type="text" id="editField" class="form-control" name="editField" value="' . $row['brgy_name'] . '">
              </div>';
        echo '<div class="form-group">
                <label for="editContactPerson">Contact Person:</label>
                <input type="text" id="editContactPerson" class="form-control" name="editContactPerson" value="' . $row['contact_person'] . '">
              </div>';
        echo '<div class="form-group">
                <label for="editContactNumber">Contact Number:</label>
                <input type="text" id="editContactNumber" class="form-control" name="editContactNumber" value="' . $row['contact_number'] . '">
              </div>';
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
