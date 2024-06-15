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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tracking Reports</title>
  <!-- Favicon -->
  <link rel="icon" href="../admin/images/HEALTHLOGO.png" type="image/x-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../vendor/plugins/fontawesome-free/css/all.min.css">
   <!-- DataTables -->
   <link rel="stylesheet" href="../vendor/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../vendor/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../vendor/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../vendor/plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../vendor/plugins/icheck-bootstrap/icheck-bootstrap.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../vendor/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../vendor/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../vendor/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../vendor/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="../vendor/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="../vendor/plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="../vendor/plugins/dropzone/min/basic.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.css">
   <!-- My custom style -->
   <link rel="stylesheet" href="../assest/mystyles.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
    <?php include '../admin/fixed-sidebar.php'?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    <h1>Tracking of Items</h1>
                    </div>
                    <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="bgy.php">Barangay Details</a></li>
                        <li class="breadcrumb-item active">Tracking</li>
                    </ol>
                    </div>
                </div>
                </div><!-- /.container-fluid -->
            </section>
            <div class="card">
                 <div class="card-header">
                    <div class="row button-container">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <a href="items_report.php" rel="noopener" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Generate report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
         <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
         </aside>
    </div>
    <!-- jQuery -->
<script src="../vendor/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../vendor/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<!-- DataTables  & Plugins -->
<script src="../vendor/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../vendor/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../vendor/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../vendor/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../vendor/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../vendor/plugins/jszip/jszip.min.js"></script>
<script src="../vendor/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../vendor/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../vendor/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../vendor/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../vendor/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../vendor/plugins/select2/js/select2.full.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../vendor/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.js"></script>
<!-- InputMask -->
<script src="../vendor/plugins/moment/moment.min.js"></script>
<script src="../vendor/plugins/inputmask/jquery.inputmask.js"></script>
<!-- date-range-picker -->
<script src="../vendor/plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../vendor/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../vendor/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../vendor/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../vendor/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../vendor/plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.js"></script>
<!-- <script src="../assest/myscript.js"></script> -->

<script>
   $(function () {
      $("#example1").DataTable({
        "responsive": true, 
         "autoWidth": false, 
         "paging": true,
        "pageLength": 5,
        "searching": true,
        "ordering": false,
        "info": true,
        "buttons": ["copy", "csv", "excel", "pdf",  "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
  </script>
</body>
</html>