<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}

$stmt = $conn->prepare("SELECT b.*, 
COALESCE(SUM(r.requested_quantity), 0) AS total_items_requested,
COALESCE(COUNT(r.item_id), 0) AS total_requested_item_ids,
COALESCE(MAX(r.contact_person), 'No record') AS contact_person,
COALESCE(MAX(r.contact_number), 'No record') AS contact_number
FROM table_brgy b
LEFT JOIN request_table r ON b.id = r.brgy_id
GROUP BY b.id
ORDER BY b.id ASC ");

$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    #viewItemsModal .modal-body {
        max-height: 80vh; 
        overflow-y: auto;
    }

    #viewItemsModal .modal-dialog {
        max-width: 80%; /* You can adjust this value to your preference */
    }

    #viewItemsModal .modal-
    content {
        width: 100%;
    }
</style>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Barangay Information </title>
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
  <!-- Toastr -->
  <!-- <link rel="stylesheet" href="../vendorplugins/toastr/toastr.min.css"> -->
</head>
<style>
    #viewItemsModal .modal-body {
        max-height: 80vh; 
        overflow-y: auto;
    }
</style>
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
                    <h1> Barangay Details</h1>
                    </div>
                    <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-dashboard"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-barangay active">/Barangay</li>
                    </ol>
                    </div>
                </div>
                </div><!-- /.container-fluid -->
                 <!-- Add this form below the heading div -->
                <div id="addbarangayForm" style="display: none;">
                    <form id="newbarangayForm" method="POST" action="">
                        <label for="barangayName">Barangay Name:</label>
                        <input type="text" id="barangayName" name="barangayName" required>
                        <label for="person">Contact Person:</label>
                        <input type="text" id="person" name="person" required>
                        <label for="number">Contact Number:</label>
                        <input type="number" id="number" name="number" required>
                        <button type="button" class="btn-outline-danger" onclick="cancelAdd()">Cancel</button>
                        <button  type="submit" class="btn-primary">Add</button>
                    </form>
                </div>
            </section>
            <div class="card">
                <div class="card-header">
                    <div class="row button-container">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="btn-group w-100 ml-auto">
                                <button type="button" id="openAddModalBtn" class="btn btn-success  col-3" onclick="openAddModal()">
                                    <i class="fas fa-plus"></i>
                                    ADD
                                </button>
                                <a href="brgy_report.php" rel="noopener" target="_blank" class="btn btn-primary col-3">
                                    <i class="fas fa-print"></i> Generate report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Barangay Name</th>
                                <th>Contact Person</th>
                                <th>Contact Number</th>
                                <th>Total Items</th>
                                <th>Total Quantity </th>
                                <th class="exclude-print">View</th>
                                <!-- <th>Edit</th> -->
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Display Records -->
                            <?php
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?= $row['brgy_name'] ?></td>
                                    <td><?= $row['contact_person'] ?></td>
                                    <td><?= $row['contact_number'] ?></td>
                                    <td><?= $row['total_requested_item_ids'] ?></td>
                                    <td><?= $row['total_items_requested'] ?></td>
                                    <td class="exclude-print">
                                        <button class="btn btn-block btn-danger btn-xs" onclick="viewItems(<?= $row['id'] ?>, '<?= $row['brgy_name'] ?>')">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                    <!-- <td class="exclude-print">
                                        <button class="btn btn-block btn-info btn-xs edit-button" data-toggle="modal" data-target="#editModal" data-bgy-id="<?= $row['id'] ?>">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </button>
                                    </td> -->
                                </tr>
                                <?php
                            }
                            ?>

                        </tbody>
                    </table>

               
                </div>
                <!-- /.card-body   -->
            </div>
        </div>
        <!-- update number -->
        </div>
         <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
         </aside>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="editModalBody">
                    <!-- Content will be dynamically loaded here using AJAX -->
                   
                </div>
            </div>
        </div>
    </div>
    <!-- Add this HTML modal to your existing code -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content for adding a new record -->
                    <form id="addForm" action="add.php" method="post">
                        <div class="form-group">
                            <label for="brgy_name">Barangay Name:</label>
                            <input type="text" class="form-control" id="brgy_name" name="brgy_name" required>
                        </div>
                    
                        <button type="button" class="btn btn-block btn-success" onclick="addRecord()">
                            <i class="fas fa-save"></i> Add Record
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add this modal at the end of your body -->
    <div class="modal fade" id="viewItemsModal" tabindex="-1" role="dialog" aria-labelledby="viewItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewItemsModalLabel">View Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="viewItemsModalBody">
                    <div class="table-responsive">
                        <!-- Your table code here -->
                        <table class="table table-bordered table-striped">
                            <!-- Table headers and body -->
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- RECORD FOR BARANAD FOR BARANA button will be appended here dynamically -->
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
<!-- BS-Stepper -->
<script src="../vendor/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../vendor/plugins/dropzone/min/dropzone.min.js"></script>
<!-- SweetAlert2 -->
<script src="../vendor/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../vendor/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Toastr -->
<!-- <script src="../vendor/plugins/toastr/toastr.min.js"></script> -->
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
        "ordering":false,
    });
});



$(document).ready(function () {
    // Event listener for the edit button using delegated event handling
    $(document).on('click', '.edit-button', function () {
        var bgyId = $(this).data('bgy-id');

        // Make an AJAX request
        $.ajax({
            url: 'fetch_record.php',
            type: 'POST',
            data: { id: bgyId },
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

    function addRecord() {
        // Change the form action to the PHP script that processes the form
        $('#addForm').attr('action', 'add.php');

        // Submit the form
        $('#addForm').submit();
    }

    

    function openAddModal() {
        // Add logic for opening the add modal
        $('#addModal').modal('show');
    }

    function viewItems(barangayId, barangayName) {
    // Fetch items for the selected barangay using AJAX
    $.ajax({
        url: 'view.php',
        type: 'POST',
        data: { barangayId: barangayId },
        success: function(response) {
            // Populate the modal with the fetched items
            $('#viewItemsModalBody').html(response);

            // Change the modal title
            var barangay = "Barangay " + barangayName;
            $('#viewItemsModalLabel').text('Record for ' + barangay);

            // Add the link to view_requests.php with the corresponding barangay ID
            var printLink =
                '<a href="view_requests.php?brgy_id=' +
                barangayId +
                '" rel="noopener" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Print</a>';

            // Append the print link to the modal footer
            $('#viewItemsModal .modal-footer').html(printLink);

            // Show the modal
            $('#viewItemsModal').modal('show');
        },
    });
}


// THE COMMENTED FUNCTIONS ARE FOR AJAX ONLY UNCOMMENT IF YOU WANT TO USE AJAX TO PROCESS DATA
    // this is for ajax functions 
    // function updateRecord() {
    //     // Get the updated field value
    //     var editedValue = $('#editField').val();
    //     var id = $('#editForm input[name="id"]').val();

    //     // Perform AJAX request
    //     $.ajax({
    //         url: 'update.php', // Replace with your server-side script handling updates
    //         type: 'POST',
    //         data: { id: id, editField: editedValue },
    //         success: function(response) {
    //             // Handle the response from the server
    //             console.log(response);

    //             // Display success message using SweetAlert
    //             Swal.fire({
    //                 icon: 'success',
    //                 title: 'Record updated successfully.',
    //                 showConfirmButton: false,
    //                 timer: 1500  // Automatically close after 1.5 seconds
    //             });

    //             // Optionally, close the modal after successful update
    //             $('#editModal').modal('hide');
    //         },
    //         error: function(error) {
    //             // Handle errors
    //             console.error(error);

    //             // Display error message using SweetAlert
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Error updating record.',
    //                 text: 'Please try again.',
    //                 confirmButtonColor: '#3085d6',
    //                 cancelButtonColor: '#d33',
    //                 confirmButtonText: 'OK'
    //             });
    //         }
    //     });
    // }

        // function openAddModal() {
        //     // Clear existing modal content
        //     $('#addModalBody').empty();

        //     // Fetch form content from addForm.php
        //     $.ajax({
        //         url: 'add.php',
        //         type: 'POST',
        //         success: function(response) {
        //         console.log(response);  // Log the response to the console

        //         // Create a new form and append the response
        //         var formContent = '<form id="addForm">' + response + '</form>';

        //         // Load the form content into the modal body
        //         $('#addModalBody').html(formContent);

        //         // Show the modal
        //         $('#addModal').modal('show');
        //     }


        //     });
        // }



//     function addRecord() {
//     // Serialize form data
//     var formData = $('#addForm').serialize();

//     // Send AJAX request to addRecord.php
//     $.ajax({
//         type: "POST",
//         url: "add.php", // Change this to the actual path
//         data: formData,
//         success: function (response) {
//             // Display success message using SweetAlert
//             Swal.fire({
//                 icon: 'success',
//                 title: 'Record added successfully.',
//                 showConfirmButton: false,
//                 timer: 1500  // Automatically close after 1.5 seconds
//             });

//             // Optionally, close the modal or do other actions
//             $('#addModal').modal('hide');
//         },
//         error: function (error) {
//             // Handle errors
//             console.error(error);

//             // Display error message using SweetAlert
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error adding record.',
//                 text: 'Please try again.',
//                 confirmButtonColor: '#3085d6',
//                 cancelButtonColor: '#d33',
//                 confirmButtonText: 'OK'
//             });
//         }
//     });
// }

// $('#addForm').submit(function(e){
//     e.preventDefault();  // Prevent the default form submission
//     addRecord();
// });




</script>

</body>
</html>