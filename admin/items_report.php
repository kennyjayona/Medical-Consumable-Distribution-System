
<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
// Assuming fetchItemOptions function is defined elsewhere
function fetchItemOptions($conn)
{
    $query = "SELECT id, item_name FROM table_item";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='{$row['id']}'>{$row['item_name']}</option>";
    }
}

// Assuming your SQL query is correct
$query = "
SELECT
    i.item_name,
    GROUP_CONCAT(DISTINCT b.brgy_name ORDER BY b.brgy_name ASC SEPARATOR '<br> ') AS barangays,
    GROUP_CONCAT(r.requested_quantity ORDER BY b.brgy_name ASC SEPARATOR '<br> ') AS quantities
FROM
    table_item i
LEFT JOIN
    request_table r ON i.id = r.item_id
LEFT JOIN
    table_brgy b ON r.brgy_id = b.id
GROUP BY
    i.item_name
    ORDER BY
    SUM(CAST(r.requested_quantity AS UNSIGNED)) DESC;";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentDate = date('F j, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Items Flow report</title>

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
   
    <h2><b>Tracking of Items Report</b></h2>
    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Barangay Name</th>
                                <th>Quantity</th>     
                            </tr>
                        </thead>
                        <tbody>
                              <?php foreach ($data as $row): ?>
                                <tr>
                                    <td><?php echo $row['item_name']; ?></td>
                                    <td><?php echo $row['barangays']; ?></td>
                                    <td><?php echo $row['quantities']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Item Name</th>
                                <th>Barangay Name</th>
                                <th>Total Quantity</th>    
                            </tr>
                        </tfoot>
                    </table>


</div>
  
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>
