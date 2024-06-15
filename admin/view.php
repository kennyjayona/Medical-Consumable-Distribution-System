<?php
// Include your database connection file
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

// Check if barangayId is set
if (isset($_POST['barangayId'])) {
    $barangayId = $_POST['barangayId'];

    // Fetch barangay name and items for the selected barangay
    $stmt = $conn->prepare("SELECT b.id, 
                           b.brgy_name, 
                            i.item_name, 
                            i.batch_number, 
                            i.exp_date,
                            r.requested_quantity, 
                            r.status,
                            d.distribution_date AS distribution_date,
                            r.request_date
                        FROM request_table r
                        LEFT JOIN table_item i ON r.item_id = i.id
                        LEFT JOIN table_brgy b ON r.brgy_id = b.id
                        LEFT JOIN  distributed_table d ON r.req_number = d.req_number
                        WHERE r.brgy_id = :barangayId;
                        ");

    $stmt->bindParam(':barangayId', $barangayId, PDO::PARAM_INT);
    $stmt->execute();


    if ($stmt->rowCount() > 0) {
      // Display items with quantity and status in an HTML table
        echo '<table class="table table-bordered table-striped">';
        echo '<thead>
                <tr>
                <th>Item Name</th>
                <th>Batch Number</th>
                <th>Expiration Date</th>
                <th>Quantity</th>
                <th>Status</th> 
                <th>Request Date</th>
                <th>Distributed Date</th>
                </tr>
            </thead>';
        echo '<tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Check if $row is not null
            if (!empty($row)) {
                echo '<tr>';
                echo '<td>' . $row['item_name'] . '</td>';
                echo '<td>' . $row['batch_number'] . '</td>';
                echo '<td>' . $row['exp_date'] . '</td>';
                echo '<td>' . $row['requested_quantity'] . '</td>';
                echo '<td>' . $row['status'] . '</td>';
                echo '<td>' . $row['request_date'] . '</td>';
                echo '<td>' . $row['distribution_date'] . '</td>';
                echo '</tr>';
            } else {
                // Handle the case where $row is null (optional)
                echo '<tr><td colspan="4">No data available</td></tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';


    } else {
        echo 'No items found for Barangay ID: ' . $barangayId;
    }
}
?>
