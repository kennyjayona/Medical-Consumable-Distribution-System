
<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
// Fetch data from distributed_table, request_table, table_brgy, and table_item
$stmt = $conn->prepare("SELECT
r.req_number AS 'Request Number',
GROUP_CONCAT(DISTINCT CONCAT(b.brgy_name, '<br> ', r.contact_person, '<br>', r.contact_number) ORDER BY r.inserted_on DESC SEPARATOR '<br>') AS 'Barangay Information',
MAX(i.batch_number) AS 'Batch number',
GROUP_CONCAT(CONCAT(i.item_name, ' ---', r.requested_quantity) ORDER BY r.inserted_on DESC SEPARATOR '<br>') AS 'Requested supplies',
MAX(i.exp_date) AS 'exp.date',
MAX(r.request_date) AS 'Request Date',
MAX(d.distribution_date) AS 'Distributed Date'
FROM
request_table r
JOIN table_brgy b ON r.brgy_id = b.id
JOIN table_item i ON r.item_id = i.id
JOIN distributed_table d ON r.req_number = d.req_number
GROUP BY
r.req_number, r.brgy_id, r.contact_person, r.contact_number
ORDER BY  
MAX(d.distribution_date) DESC;


                        ");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentDate = date('F j, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Distribution report</title>

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
   
    <h2><b>Medical Distribution Report</b></h2>
    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Request Number</th>
                                <th>Baranggay Information</th>
                                <th>Batch number</th>
                                <th>Requested supplies</th>
                                <th>exp.date</th>
                                <th>Request Date</th>
                                <th>Distributed Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Loop through the query result and display data in the table
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td>{$row['Request Number']}</td>";
                                    echo "<td>{$row['Barangay Information']}</td>";
                                    echo "<td>{$row['Batch number']}</td>";
                                    echo "<td>{$row['Requested supplies']}</td>";
                                    echo "<td>{$row['exp.date']}</td>";
                                    echo "<td>{$row['Request Date']}</td>";
                                    echo "<td>{$row['Distributed Date']}</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                        <tfoot>
                                 <th>Request Number</th>
                                <th>Baranggay Information</th>
                                <th>Batch number</th>
                                <th>Requested supplies</th>
                                <th>exp.date</th>
                                <th>Request Date</th>
                                <th>Distributed Date</th>
                        </tfoot>
                    </table>


</div>
  
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>
