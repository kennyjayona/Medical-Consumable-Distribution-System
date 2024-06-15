<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
// Check if REQUEST_METHOD is set before using it
$requestMethod = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";

if ($requestMethod === "POST") {
    // Retrieve form data
    $brgy_id = isset($_POST["barangay"]) ? $_POST["barangay"] : "";
    $contact_person = isset($_POST["contact-person"]) ? $_POST["contact-person"] : "";
    $contact_number = isset($_POST["contact-number"]) ? $_POST["contact-number"] : "";
    $request_date = isset($_POST["request-date"]) ? $_POST["request-date"] : "";
    $medicalSupplies = isset($_POST["medicalSupplies"]) ? $_POST["medicalSupplies"] : [];
    $itemQuantities = isset($_POST["item_quantity"]) ? $_POST["item_quantity"] : [];

    // Generate a unique request number ID
    $req_number = sprintf("%s%09d", str_replace('.', '', microtime(true)), mt_rand(0, 999));

    // Initialize an error variable
    $error_message = "";

    // Perform basic validation
    if (empty($brgy_id) || empty($contact_person) || empty($contact_number) || empty($request_date)) {
        // Handle the error, e.g., by echoing an error message or redirecting
        $error_message = "All fields are required!";
    } else {
        // Validate contact number format
        if (!preg_match("/^09\d{9}$/", $contact_number)) {
            $error_message = "Invalid contact number format!";
        } else {
            // Convert dates to the correct format
            $formatted_request_date = date('Y-m-d', strtotime($request_date));
            $formatted_inserted_on = date('Y-m-d H:i:s');

            $stmt = $conn->prepare("INSERT INTO request_table (req_number, brgy_id, item_id, requested_quantity, contact_person, contact_number, request_date, inserted_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($medicalSupplies as $item) {
                $item_id = $item;
                $quantity = isset($itemQuantities[$item]) ? $itemQuantities[$item] : 0;

                // Fix the order of parameters in the execute method
                $stmt->execute([$req_number, $brgy_id, $item_id, $quantity, $contact_person, $contact_number, $formatted_request_date, $formatted_inserted_on]);
            }

            echo json_encode(['success' => true]);
            exit;
        }
    }

    // Echo the error message to be captured by JavaScript
    echo json_encode(['error' => $error_message]);
    exit;
} else {
    // Handle invalid request method (optional)
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}
?>
