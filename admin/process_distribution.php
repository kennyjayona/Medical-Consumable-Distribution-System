<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Set the timezone to 'Asia/Manila'
    date_default_timezone_set('Asia/Manila');

    // Retrieve selected request numbers from the AJAX request
    $selectedRequests = isset($_POST["selectedRequests"]) ? $_POST["selectedRequests"] : [];

    // Check if any requests are selected
    if (empty($selectedRequests)) {
        echo json_encode(['success' => false, 'message' => 'No requests selected for distribution']);
        exit;
    }

    // Perform the distribution process and insert into distributed_table
    try {
        $conn->beginTransaction();

        // Insert into distributed_table for each selected request
        foreach ($selectedRequests as $reqNumber) {
            // Fetch details from request_table
            $stmtRequest = $conn->prepare("SELECT brgy_id, item_id, contact_person, contact_number, request_date, requested_quantity FROM request_table WHERE req_number = ?");
            $stmtRequest->execute([$reqNumber]);
            $requestDetails = $stmtRequest->fetch(PDO::FETCH_ASSOC);

            // Insert into distributed_table
            $stmtDistributed = $conn->prepare("INSERT INTO distributed_table (req_number, brgy_id, item_id, contact_person, contact_number, distribution_date, requested_quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtDistributed->execute([
                $reqNumber,
                $requestDetails['brgy_id'],
                $requestDetails['item_id'],
                $requestDetails['contact_person'],
                $requestDetails['contact_number'],
                date('Y-m-d H:i:s'), // Assuming today's date for distribution_date
                $requestDetails['requested_quantity']
            ]);
        }

        // Update the status in request_table
        $placeholders = str_repeat('?, ', count($selectedRequests) - 1) . '?';
        $stmtUpdateStatus = $conn->prepare("UPDATE request_table SET status = 'distributed' WHERE req_number IN ($placeholders)");
        $stmtUpdateStatus->execute($selectedRequests);

        $conn->commit();

        // Assuming success for demonstration purposes
        echo json_encode(['success' => true, 'message' => 'Requests distributed successfully']);
        exit;
    } catch (PDOException $e) {
        // Handle database error
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error processing the request. Please try again later.']);
        exit;
    }
} else {
    // Handle invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}
?>
