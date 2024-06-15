<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
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
   
    <h2><b>Barangay Report</b></h2>
    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Barangay Name</th>
                                <th>Contact Person</th>
                                <th>Contact Number</th>
                                <th>Total Items</th>
                                <th>Total Quantity </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                         $stmt = $conn->prepare("SELECT b.*, 
                         COALESCE(SUM(r.requested_quantity), 0) AS total_items_requested,
                         COALESCE(COUNT(DISTINCT r.item_id), 0) AS total_requested_item_ids,
                         COALESCE(MAX(r.contact_person), 'N/A') AS contact_person,
                         COALESCE(MAX(r.contact_number), 'N/A') AS contact_number
                        FROM table_brgy b
                        LEFT JOIN request_table r ON b.id = r.brgy_id
                        GROUP BY b.id");

                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>{$row['brgy_name']}</td>";
                        echo "<td>{$row['contact_person']}</td>";
                        echo "<td>{$row['contact_number']}</td>";
                        echo "<td>{$row['total_requested_item_ids']}</td>"; 
                        echo "<td>{$row['total_items_requested']}</td>";
                        echo "</tr>";
                        }

                        // Display "No Record" if there is no data
                        if ($stmt->rowCount() == 0) {
                        echo '<tr><td colspan="5">No Record</td></tr>';
                        }


                        ?>

                        </tbody>
                    </table>


</div>
  
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>
