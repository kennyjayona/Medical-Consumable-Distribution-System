<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
$stm = $conn->prepare("SELECT
                            table_item.*,
                            COALESCE(SUM(request_table.requested_quantity), 0) AS total_requested_quantity,
                            (table_item.item_quantity - COALESCE(SUM(request_table.requested_quantity), 0)) AS available_quantity
                            FROM
                            table_item
                            LEFT JOIN
                            request_table ON table_item.id = request_table.item_id
                            GROUP BY
                            table_item.id
                            ORDER BY
                            exp_date DESC;"); // Change this line to order by exp_date
$stm->execute();
$result = $stm->fetchAll(PDO::FETCH_ASSOC);

// ... (remaining code)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Manage supplies</title>
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
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
   
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
                    <h1> Medical Supply Inventory</h1>
                    </div>
                    <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Supply</li>
                    </ol>
                    </div>
                </div>
                </div><!-- /.container-fluid -->
            </section>
            <div class="card">  
                <!-- /.card-header -->  
                <!-- <div class="card-header">
                    <div class="row button-container">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <button type="button" id="openAddModalBtn" class="btn btn-success btn-sm" onclick="openAddModal()">
                                <i class="fas fa-plus"></i> ADD
                            </button>
                        </div>
                    </div>
                </div> -->
                <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Batch Number</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Expiration Date</th>
                            <th>Requested Quantity</th>
                            <th>Available Quantity</th>
                            <th>Status</th>
                            <th>Last Update</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row) : ?>
                            <tr id="row_<?php echo $row['id']; ?>">
                                <td class="batch-number"><?php echo $row['batch_number']; ?></td>
                                <td class="name-column"><?php echo $row['item_name']; ?></td>
                                <td class="quantity-column"><?php echo $row['item_quantity']; ?></td>
                                <td class="exp-date">
    <?php
    $expirationDate = $row['exp_date'];
    $currentDate = date('Y-m-d');

    // Add an expiration sign based on the comparison with the current date
    $expirationSign = ($expirationDate < $currentDate) ? '<span style="color: red; font-weight: bold;">X</span>' : '';

    // Display the expiration sign and date
    echo $expirationSign . ' ' . $expirationDate;
    ?>
</td>


                                <td class="requested-quantity-column"><?php echo $row['total_requested_quantity']; ?></td>
                                <td class="available_quantity"><?php echo $row['available_quantity']; ?></td>
                                <td class="status-column">
                                    <?php
                                    $availableQuantity = $row['available_quantity'];

                                    if ($availableQuantity >= 100) {
                                        echo '<span class="badge badge-success">Sufficient</span>';
                                    } elseif ($availableQuantity > 10 && $availableQuantity < 100) {
                                        echo '<span class="badge badge-warning">Warning</span>';
                                    } elseif ($availableQuantity <= 10) {
                                        echo '<span class="badge badge-danger">Out of Stock</span>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $row['added_date']; ?></td>
                                <td>
                                    <button class="btn btn-block btn-info btn-xs edit-button" data-toggle="modal" data-target="#editModal" data-id="<?php echo $row['id']; ?>">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Batch Number</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Expiration Date</th>
                            <th>Requested Quantity</th>
                            <th>Available Quantity</th>
                            <th>Status</th>
                            <th>Last Update</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>


                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- update quantity -->
        </div>
         <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
         </aside>
    </div>
    <!-- Modals for add and update -->
    <!-- Modal content in supply.php -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editModalBody">
      
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Content for adding a new record -->
                <form id="addForm" action="addSupply.php" method="post">
                    <div class="form-group">
                        <label for="item_name">Item Name:</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" required>
                    </div>
                    <div class="form-group">
                        <label for="item_quantity">Item Quantity:</label>
                        <input type="text" class="form-control" id="item_quantity" name="item_quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="batch_number">Batch Number:</label>
                        <input type="text" class="form-control" id="batch_number" name="batch_number" required>
                    </div>
                    <div class="form-group">
                        <label for="exp_date">Expiration Date:</label>
                        <input type="date" class="form-control" id="exp_date" name="exp_date" required>
                    </div>
                    <button type="submit" class="btn btn-block btn-success">
                        <i class="fas fa-save"></i> Save
                    </button>
                </form>

            </div>
        </div>
    </div>
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
<!-- SweetAlert2 -->
<script src="../vendor/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../vendor/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- BS-Stepper -->
<script src="../vendor/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../vendor/plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.js"></script>
<!-- <script src="../assest/myscript.js"></script> -->
<!-- Your existing HTML and other scripts -->

<script>
$(function () {
    // Create DataTable
    var table = $("#example1").DataTable({
        "responsive": true,
        "autoWidth": false,
        "paging": true,
        "pageLength": 5,
        "ordering":false,
        "buttons": [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':visible:not(.exclude-print)'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:not(.exclude-print)'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible:not(.exclude-print)'
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':visible:not(.exclude-print)'
                },
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:not(.exclude-print)'
                },
            },
            "colvis"
        ]
    });
    // Move the buttons container creation and appending outside the DataTable initialization
    table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    // Create the "ADD" button
    var addButton = $('<button type="button" id="openAddModalBtn" class="btn btn-success" onclick="openAddModal()"><i class="fas fa-plus"></i> ADD</button>');

    // Append the "ADD" button to the DataTable wrapper
    addButton.appendTo('#example1_wrapper .col-md-6:eq(0)');

    
});


    function addRecord() {
        // Change the form action to the PHP script that processes the form
        $('#addForm').attr('action', 'addSupply.php');

        // Submit the form
        $('#addForm').submit();
    }

    

    function openAddModal() {
        // Add logic for opening the add modal
        $('#addModal').modal('show');
    }

   


    $(document).ready(function () {
    // Event listener for the edit button using delegated event handling
    $(document).on('click', '.edit-button', function () {
        var id = $(this).data('id');

        // Make an AJAX request
        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            data: { id: id },
            success: function (response) {
                // Update the modal body with the fetched data
                $('#editModalBody').html(response);

                // Show the modal
                $('#editModal').modal('show');
            },
            error: function () {
                alert('Error fetching data.');
            }
        });
    });
});




// this code is for only when you use ajax
// function fetchSingleRecord(id) {
//     $.ajax({
//         url: 'fetchSingleRecord.php',
//         type: 'POST',
//         data: { id: id },
//         dataType: 'json',
//         success: function(response) {
//             // Update the corresponding table cell with the new data
//             var rowId = 'row_' + id;
//             $('#' + rowId + ' .name-column').text(response.item_name);
//             $('#' + rowId + ' .quantity-column').text(response.item_quantity);
//             $('#' + rowId + ' .available_quantity').text(response.available_quantitys);
//              // Update status based on available quantity
//              updateStatus(id, response.available_quantity);
//         },
//         error: function(error) {
//             console.error(error);
//         }
//     });
// }


function updateRecord() {
    // Get the updated field values
    var editedName = $('#editName').val();
    var editedQuantity = $('#editQuantity').val();
    var id = $('#editForm input[name="id"]').val();
    
    // Perform AJAX request
    $.ajax({
        url: 'updateSupply.php',
        type: 'POST',
        data: { id: id, editField: editedName, editQuantity: editedQuantity },
        success: function(response) {
            // Handle the response from the server
            console.log(response);

            // Display success message using SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Record updated successfully.',
                showConfirmButton: false,
                timer: 1500  // Automatically close after 1.5 seconds
            });

               // Fetch and update the latest record details
               fetchSingleRecord(id);
               
            // Update the quantity and status dynamically in the table
            updateStatus(id, parseInt(editedQuantity));

            // Optionally, close the modal after successful update
            $('#editModal').modal('hide');
        },
        error: function(error) {
            // Handle errors
            console.error(error);

            // Display error message using SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error updating record.',
                text: 'Please try again.',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        }
    });
}

function updateStatus(id, newQuantity) {
    // Perform AJAX request to get updated status and available quantity
    $.ajax({
        url: 'updateStatus.php',
        type: 'POST',
        data: { id: id, newQuantity: newQuantity, requestedQuantity: 0 }, // Set requestedQuantity as needed
        dataType: 'json',
        success: function(response) {
            // Update available quantity in the DOM
            $("#row_" + id + " .quantity-column").text(response.availableQuantity);

            // Update status in the DOM
            var statusColumn = $("#row_" + id + " .status-column");
            statusColumn.empty(); // Clear existing content
            statusColumn.html(response.status);
        },
        error: function(error) {
            console.error(error);
        }
    });
}


</script>





</body>
</html>