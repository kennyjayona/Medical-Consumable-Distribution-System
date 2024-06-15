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
GROUP_CONCAT(CONCAT(i.batch_number, ' ') ORDER BY r.inserted_on DESC SEPARATOR '<br>') AS 'Batch number',
GROUP_CONCAT(CONCAT(i.item_name, ' --', r.requested_quantity) ORDER BY r.inserted_on DESC SEPARATOR '<br>') AS 'Requested supplies',
GROUP_CONCAT(CONCAT(i.exp_date, ' -') ORDER BY r.inserted_on DESC SEPARATOR '<br>') AS 'exp.date',                 
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
MAX(r.inserted_on) DESC;  -- Order by the insertion timestamp
");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        </style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Distribution Report</title>
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
                        <h1> Distributed List</h1>
                        </div>
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="list_request.php">Request List</a></li>
                            <li class="breadcrumb-item active">Distributed list</li>
                        </ol>
                        </div>
                    </div>
                </div>
            </section>
            <div class="card">
                <div class="card-header">
                    <div class="row button-container">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <a href="distributed_reports.php" rel="noopener" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Generate report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Request Number</th>
            <th>Barangay Information</th>
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
        <tr>
            <th>Request Number</th>
            <th>Barangay Information</th>
            <th>Batch number</th>
            <th>Requested supplies</th>
            <th>exp.date</th>
            <th>Request Date</th>
            <th>Distributed Date</th>
        </tr>
    </tfoot>
</table>
                </div>
                <!-- <br>
                <br>
                <div class="card-footer">
                    <div class="row button-container">
                        <div class="col-md-4">add
                        </div> 
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                        <h3 class="card-title">  Signature : </h3>
                        </div>
                    </div>
                </div> -->
                <!-- /.card-body -->
            </div>
            
            
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
<script src="../assest/myscript.js"></script>

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
        "buttons": ["copy", "csv", "excel", "pdf", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
  </script>
</body>
</html>