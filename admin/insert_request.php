<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
// Function to fetch barangay options
function fetchBarangayOptions($conn)
{
    $query = "SELECT id, brgy_name FROM table_brgy";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='{$row['id']}'>{$row['brgy_name']}</option>";
    }
}
date_default_timezone_set('Asia/Manila');



?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Insert request</title>
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
   <!-- My custom style -->
   <!-- <link rel="stylesheet" href="../assest/mystyles.css"> -->
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
  <?php include '../admin/fixed-sidebar.php'?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
       <!-- Add the success message container here -->
       <!-- <div class="success-message-container">
          <div class="success-icon"></div>
          <span>Data inserted successfully !</span>
      </div> -->
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Medical Request Form</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="supply.php">Inventory</a></li>
                <li class="breadcrumb-item active">Insert Request</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <form id="insert-request" method="post" action="../admin/process_insertReq.php">
            <div class="card card-default">
             <div class="card-header">
                <!-- Submit and Cancel Buttons -->
                <div class="row button-container">
                  <div class="col-md-10"></div> <!-- Empty column to push buttons to the right -->
                  <div class="col-md-1">
                   <input type="submit" id="submitBtn" class="btn btn-block btn-primary btn-sm" value="Submit">
                  </div>
                  <div class="col-md-1">
                    <button type="reset" id="cancelBtn" class="btn btn-block btn-outline-danger btn-sm">Cancel</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <!-- Barangay  -->
                  <div class="col-md-3">
                    <div class="form-group">
                     <label for="barangay">Barangay Name:</label>
                      <select id="barangay" name="barangay" class="form-control select2" style="width: 100%;">
                      <?php fetchBarangayOptions($conn); ?>
                      </select>
                      <span id="barangay-error" class="text-danger"></span> <!-- Error message container -->
                    </div>
                  </div>
                  <!-- Contact Person -->
                  <div class="col-md-3">
                    <div class="form-group">
                    <label for="contact-person">Contact Person:</label>
                    <input type="text" id="contact-person" name="contact-person" class="form-control" placeholder="Enter contact person name">
                    <span id="contact-person-error" class="text-danger"></span> <!-- Error message container -->
                    </div>
                  </div>
                  <!-- Contact number -->
                  <div class="col-md-3">
                    <div class="form-group">
                        <label for="contact-number">Contact Number:</label>
                        <input type="text" id="contact-number" name="contact-number" class="form-control" placeholder="Enter active contact number" pattern="09\d{9}" title="Enter a valid 11-digit contact number starting with '09'" required>
                        <span id="contact-number-error" class="text-danger"></span> <!-- Error message container -->                    
                    </div>
                </div>
                  <!-- Request date -->
                  <div class="col-md-3">
                    <div class="form-group">
                        <label for="request-date">Request Date:</label>
                        <div class="input-group date" id="request-date" data-target-input="nearest">
                            <input type="text" id="request-date" name="request-date" class="form-control datetimepicker-input" data-target="#request-date" placeholder="<?php echo date('Y-m-d'); ?>" />
                            <div class="input-group-append" data-target="#request-date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                            </div>
                        </div>
                        <span id="request-date-error" class="text-danger"></span>
                    </div>
                  </div>

                  <!-- /.col -->
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Requested Supplies</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                        <table id="medicalSuppliesTable" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Select</th>
            <th>Expiration Date</th>
            <th>Item Name</th>
            <th>Available Quantity</th>
            <th>Request Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Initialize the array to store item IDs requiring validation
        $itemIdsRequiringQuantityValidation = [];
        $threshold = 1;
        $stmt = $conn->prepare("SELECT table_item.*, COALESCE(SUM(request_table.requested_quantity), 0) AS total_requested_quantity
                                FROM table_item
                                LEFT JOIN request_table ON table_item.id = request_table.item_id
                                GROUP BY table_item.id
                                ORDER BY exp_date DESC");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemQuantity = isset($row['item_quantity']) ? intval($row['item_quantity']) : 0;
            $requestedQuantity = isset($row['total_requested_quantity']) ? intval($row['total_requested_quantity']) : 0;

            $totalQuantity = $itemQuantity - $requestedQuantity;

            // Skip items with zero quantity
            if ($totalQuantity <= 0) {
                continue;
            }

            echo "<tr>";
            echo "<td><input type='checkbox' name='medicalSupplies[]' value='{$row['id']}' " . ($row['exp_date'] < date('Y-m-d') ? 'disabled' : '') . "></td>";
            echo "<td style='color: " . ($row['exp_date'] < date('Y-m-d') ? 'red' : 'black') . "'>{$row['exp_date']}" . ($row['exp_date'] < date('Y-m-d') ? ' (Expired)' : '') . "</td>";
            echo "<td>{$row['item_name']}</td>";
            echo "<td>{$totalQuantity}</td>";

            // Check if the item ID requires a quantity input
            if ($totalQuantity < $threshold) {
                $itemIdsRequiringQuantityValidation[] = $row['id'];
                echo "<td><input type='number' name='item_quantity[{$row['id']}]' max='{$totalQuantity}' value='0' " . ($row['exp_date'] < date('Y-m-d') ? 'disabled' : 'required') . "></td>";
            } else {
                echo "<td><input type='number' name='item_quantity[{$row['id']}]' max='{$totalQuantity}' value='0' " . ($row['exp_date'] < date('Y-m-d') ? 'disabled' : '') . "></td>";
            }

            echo "</tr>";
        }
        ?>
    </tbody>
</table>

                        </div>
                    </div>
                </div>
              </div>
            </div>
          
          </form>
        </div>
      </section>
    </div>

          

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

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
<script>



  $(document).ready(function() {
    // Function to show SweetAlert error
    function showErrorAlert(message) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Submission',
        text: message,
      });
    }

    // Function to disable checkboxes with zero available quantity
    function disableZeroQuantityCheckboxes() {
  $('input[name="medicalSupplies[]"]').each(function() {
    const itemId = $(this).val();
    const availableQuantity = parseInt($(this).closest('tr').find('td:nth-child(3)').text());

    if (availableQuantity  <= 10) {
      // Disable the checkbox
      $(this).prop('disabled', true);

      // Display "Out of Stock" message in the corresponding cell
      $(this).closest('tr').find('td:nth-child(3)').html('<span class="badge badge-danger">Out of Stock</span>');
      
      // Optionally, you can also hide the input field if it exists
      $(this).closest('tr').find('input[name^="item_quantity["]').hide();
    }
  });
}

    // Function to check if at least one checkbox is checked
    function isAnyCheckboxChecked() {
      return $('input[name="medicalSupplies[]"]:checked').length > 0;
    }

    // Disable checkboxes on page load
    disableZeroQuantityCheckboxes();

    // Submit form using Ajax
    $("#insert-request").submit(function(e) {
      e.preventDefault();
      e.stopPropagation();

      // Flag to check invalid quantities
      let invalidQuantity = false;

    // Check each quantity input
    $('input[name^="item_quantity["]').each(function() {
      const itemId = this.name.match(/\[(\d+)\]/)[1];
      const checkbox = $('input[name="medicalSupplies[]"][value="' + itemId + '"]');
      const availableQuantity = parseInt(checkbox.closest('tr').find('td:nth-child(3)').text());
      const requestedQuantity = parseInt($(this).val());

      // If checkbox is checked
      if (checkbox.is(':checked')) {
        // If quantity is not a number or is less than or equal to zero
        if (isNaN(requestedQuantity) || requestedQuantity <= 0) {
          invalidQuantity = true;
          showErrorAlert('Please enter a valid quantity greater than zero for the selected medical supply.');
        } else if (requestedQuantity > availableQuantity) {
          invalidQuantity = true;
          showErrorAlert('The desired quantity is more than the available quantity for some items.');
        }
      }
    });

   

      // Check if at least one checkbox is checked
      if (!isAnyCheckboxChecked()) {
        invalidQuantity = true;
        showErrorAlert('Please select at least one medical supply.');
      }

      // If there are invalid quantities, don't proceed with the Ajax request
      if (invalidQuantity) {
        return;
      }

      // Perform Ajax request
      $.ajax({
        type: "POST",
        url: "process_insertReq.php",
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.error) {
            // Handle error
            $("#contact-number-error").text(response.error);
          } else if (response.success) {
            // Handle success
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Request submitted successfully!',
              showCloseButton: true,
              showCancelButton: false,
              showConfirmButton: false,
              customClass: 'swal-wide',
              html: '<a href="list_request.php" class="btn btn-primary">View</a>'
            });
            // Clear the form fields
            $("#insert-request")[0].reset();
            // Disable checkboxes with zero available quantity after successful submission
            disableZeroQuantityCheckboxes();
          }
        },
        error: function(error) {
          // Handle errors (if any)
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while processing your request. Please try again later.',
          });
          console.error("Ajax request failed:", error);
        }
      });
    });

    // Initialize Select2 Elements
    $('.select2').select2()

    // Initialize Select2 Elements with Bootstrap 4 theme
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    // Date picker
    $('#request-date').datetimepicker({
      format: 'L'
    });

    // Initialize DataTable for medical supplies table
    $('#medicalSuppliesTable').DataTable({
      "paging": true,
      "pageLength": 6,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true // Adjust the number of rows per page as needed
    });
  });
  
</script>



</body>
</html>
