<?php

include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

// Retrieve barangay ID from the URL parameter
$barangayId = isset($_GET['brgy_id']) ? intval($_GET['brgy_id']) : 0;

// Fetch barangay name for the specified barangay ID
$stmtBrgy = $conn->prepare("SELECT brgy_name FROM table_brgy WHERE id = :barangayId");
$stmtBrgy->bindParam(':barangayId', $barangayId, PDO::PARAM_INT);
$stmtBrgy->execute();
$barangayName = $stmtBrgy->fetchColumn();

// Fetch requests for the specified barangay
$stmt = $conn->prepare("
                        SELECT b.id, 
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
                        LEFT JOIN distributed_table d ON r.req_number = d.req_number
                        WHERE r.brgy_id = :barangayId;
                        ");
$stmt->bindParam(':barangayId', $barangayId, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentDate = date('F j, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Barangay report</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../vendor/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.min.css">
</head>
<style>

.heading {
    text-align: center;
  }

  .heading img {
    display: block;
    margin: 0 auto; /* Center the image horizontally */
  }

  .heading p {
    margin: 5px 0; /* Adjust as needed */
  }
  h2{
    text-align: center;
  }
</style>
<body>
    <div class="wrapper">
        <div class="heading">
            <img src="../admin/images/HEALTHLOGO.png" alt="MCDS Logo" height="150" width="150">
            <p> Address: Municipality of Manapla, Negross Occidental</p>
            <p>Phone number: 0956 214 2876</p>
            <p>Date:<?php echo $currentDate; ?></p>
        </div>
        <hr>
    
        <h2><b><?php echo $barangayName; ?> Record</b></h2>
        <!-- Display requests in a table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Batch Number</th>
                    <th>Expiration Date</th>
                    <th>Requested Quantity</th>
                    <th>Status</th>
                    <th>Reuest Date</th>
                    <th>Distibuted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['item_name'] ?></td>
                        <td><?= $row['batch_number'] ?></td>
                        <td><?= $row['exp_date'] ?></td>
                        <td><?= $row['requested_quantity'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['request_date'] ?></td>
                        <td><?= $row['distribution_date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>
