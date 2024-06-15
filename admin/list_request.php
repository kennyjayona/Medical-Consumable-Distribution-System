<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
// Execute the SQL query
$query = "
        SELECT
        r.req_number,
        GROUP_CONCAT(DISTINCT CONCAT( b.brgy_name, '<br> ',r.contact_person, '<br>', r.contact_number) ORDER BY r.inserted_on DESC SEPARATOR '<br>') AS barangay_info,
        GROUP_CONCAT(CONCAT(i.item_name, ' - ', r.requested_quantity) ORDER BY r.inserted_on DESC SEPARATOR '<br>') AS item_list,
        MAX(r.request_date) AS request_date,
        MAX(r.inserted_on) AS inserted_on,
        MAX(r.status) AS status
        FROM
        request_table r
        JOIN
        table_brgy b ON r.brgy_id = b.id
        JOIN
        table_item i ON r.item_id = i.id
        GROUP BY
        r.req_number, brgy_id, contact_person, contact_number
        HAVING
        MAX(r.status) = 'pending'
        ORDER BY
        MAX(r.inserted_on) DESC;




";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> RequestList</title>
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
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
                    <h1> Medical Request List</h1>
                    </div>
                    <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="insert_request.php">Insert Request</a></li>
                        <li class="breadcrumb-item active">Request List</li>
                    </ol>
                    </div>
                </div>
                </div><!-- /.container-fluid -->
            </section>
            <form id="distribute" method="post" action="">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Barangay level </h3>
                        <!-- Submit and Cancel Buttons -->
                        <div class="row button-container">
                            <div class="col-md-8"></div> <!-- Empty column to push buttons to the right -->
                            <div class="col-md-2">
                                <button type="submit" id="distribute" class="btn btn-block btn-primary btn-sm">Distribute</button>  
                            </div>
                            <div class="col-md-2">
                                <button type="reset" id="cancelBtn" class="btn btn-block btn-outline-danger btn-sm">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Request Number</th>
                                    <th>Barangay Information</th>
                                    <th>Requested Supplies</th>
                                    <th>Request Date</th>
                                    <!-- <th>Date inserted</th> -->
                                    <!-- <th>Status</th> -->
                                    <th><input type="checkbox" id="selectAll"> <label for="selectAll">Select All</label></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                // Loop through the query result and display data in the table
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>{$row['req_number']}</td>";
                                    echo "<td>{$row['barangay_info']}</td>";
                                    echo "<td>{$row['item_list']}</td>";
                                    echo "<td>{$row['request_date']}</td>";
                                    // echo "<td>{$row['inserted_on']}</td>";
                                    // echo "<td>{$row['status']}</td>";
                                    echo "<td><input type='checkbox' name='selectedRequests[]' value='{$row['req_number']}'></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th> Request Number</th>
                                    <th>Barangay Information</th>
                                    <th>Requested Supplies</th>
                                    <th>Request Date</th>
                                    <!-- <th>Date inserted</th> -->
                                    <!-- <th> Status</th> -->
                                    <th> Select </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </form>
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
<!-- SweetAlert2 -->
<script src="../vendor/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../vendor/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
$(document).ready(function() {

     // Select All checkbox behavior
     $("#selectAll").change(function() {
        $("input[name='selectedRequests[]']").prop('checked', $(this).prop('checked'));
    });

    // Submit form using Ajax
    $("#distribute").submit(function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Get selected request numbers
        var selectedRequests = $("input[name='selectedRequests[]']:checked").map(function() {
            return $(this).val();
        }).get();

         // Check if any requests are selected
         if (selectedRequests.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'No requests selected for distribution',
            });
            return;
        }

        // Perform Ajax request
        $.ajax({
            type: "POST",
            url: "process_distribution.php",
            data: { selectedRequests: selectedRequests },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                     // Handle success with SweetAlert
                     Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    });
        
                    // Remove selected rows from the table
                    selectedRequests.forEach(function(reqNumber) {
                        $('#example1 tbody tr:has(td:first-child:contains("' + reqNumber + '"))').remove();
                    });

                      // Check if there are no more records
                      if ($('#example1 tbody tr').length === 0) {
                        // Display indication that there are no more records
                        $('#example1 tbody').html('<tr><td colspan="7">No more records</td></tr>');
                    }

                    // Optionally, you can perform additional actions like updating the UI
                } else {
                    // Handle failure
                    // Handle failure with SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                }

                // Clear the form fields
              $("#distribute")[0].reset();
            },
            error: function(error) {
                // Handle errors (if any)
                alert("An error occurred while processing your request. Please try again later.");
                console.error("Ajax request failed:", error);
            }
        });
    });
});


$(function () {
    $("#example1").DataTable({
        "responsive": true,
        "paging": true,
        "pageLength": 5,
        "autoWidth": false,
        "order": [[4, "desc"]], // Sort by the 5th column (inserted_on) in descending order
        "buttons": [
            "copy",
            "csv",
            "excel",
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Select)
                },
                customize: function (doc) {
                    // Remove the Select column from the PDF
                    doc.content[1].table.body.forEach(function (row) {
                        row.splice(-1, 1); // Remove the last element (Select column)
                    });
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Select)
                },
                customize: function (win) {
                    // Remove the Select column from the print view
                    $(win.document.body).find('table tr:last th:last, table tr:last td:last').remove();
                }
            },
            "colvis"
        ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});


  </script>
</body>
</html>