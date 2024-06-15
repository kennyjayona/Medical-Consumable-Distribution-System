<?php
include 'conn.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
}
// Function to fetch total pending requests
function getTotalPendingRequests($conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as totalPending FROM request_table WHERE status = 'Pending'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['totalPending'];
}

// Function to fetch total distributed requests
function getTotalDistributedRequests($conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as totalDistributed FROM request_table WHERE status = 'Distributed'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['totalDistributed'];
}

// Function to fetch total items
function getTotalItems($conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as totalItems FROM table_item");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['totalItems'];
}

// Function to fetch total barangays
function getTotalBarangays($conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as totalBarangays FROM table_brgy");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['totalBarangays'];
}

// Function to fetch monthly item requests
function getMonthlyItemRequests($conn)
{
    $stmt = $conn->prepare("
        SELECT
            MONTH(request_date) as month,
            item_id,
            item_name,
            COUNT(*) as totalRequests
        FROM
            request_table
            JOIN table_item ON request_table.item_id = table_item.id
        GROUP BY
            MONTH(request_date),
            item_id,
            item_name
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Function to fetch total requested and distributed items per barangay
function getBarangayData($conn)
{
    $stmt = $conn->prepare("
        SELECT
            brgy_id,
            brgy_name,
            SUM(requested_quantity) as totalRequested,
            SUM(CASE WHEN status = 'Distributed' THEN requested_quantity ELSE 0 END) as totalDistributed
        FROM
            request_table
        JOIN table_brgy ON request_table.brgy_id = table_brgy.id
        GROUP BY
            brgy_id
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch data
$totalPending = getTotalPendingRequests($conn);
$totalDistributed = getTotalDistributedRequests($conn);
$totalItems = getTotalItems($conn);
$totalBarangays = getTotalBarangays($conn);
$monthlyItemRequests = getMonthlyItemRequests($conn);
$barangayData = getBarangayData($conn);

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MCD SYSTEM | Dashboard</title>
  <!-- Favicon -->
  <link rel="icon" href="../admin/images/HEALTHLOGO.png" type="image/x-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../vendor/plugins/fontawesome-free/css/all.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../vendor/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../vendor/plugins/icheck-bootstrap/icheck-bootstrap.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../vendor/plugins/jqvmap/jqvmap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../vendor/plugins/overlayScrollbars/css/OverlayScrollbars.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../vendor/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../vendor/plugins/summernote/summernote-bs4.css">


</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../admin/images/HEALTHLOGO.png" alt="MCDS Logo" height="300" width="300">
  </div>
<?php include '../admin/fixed-sidebar.php'?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $totalPending; ?></h3>
                        <p>Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <a href="list_request.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $totalDistributed; ?></h3>
                        <p>Distributed</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="distribution.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $totalBarangays; ?></h3>
                        <p>Barangay</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="bgy.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo $totalItems; ?></h3>
                        <p>Medical Supply</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-medkit"></i>
                    </div>
                    <a href="supply.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row charts -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card bg-gradient-default">
                <div class="card-header border-0">
                    <h3 class="card-title">
                      <i class="fa-solid fa-chart-column"></i>
                       Current Month Trend
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="itemRequestsChart" style="height: 350px; width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">
            <!-- Pie Chart card -->
            <div class="card bg-gradient-default">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Barangay Details Chart
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="barangay-pie-chart" style="height: 350px; width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../vendor/plugins/jquery/jquery.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../vendor/plugins/jquery-ui/jquery-ui.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../vendor/plugins/bootstrap/js/bootstrap.bundle.js"></script>
<!-- ChartJS -->
<script src="../vendor/plugins/chart.js/Chart.bundle.js"></script>
<!-- Sparkline -->
<script src="../vendor/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../vendor/plugins/jqvmap/jquery.vmap.js"></script>
<script src="../vendor/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../vendor/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../vendor/plugins/daterangepicker/daterangepicker.js"></script>
<script src="../vendor/plugins/moment/moment.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../vendor/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../vendor/plugins/summernote/summernote-bs4.js"></script>
<!-- overlayScrollbars -->
<script src="../vendor/plugins/overlayScrollbars/js/jquery.overlayScrollbars.js"></script>
<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.js"></script>
  <script>
    // Data fetched from PHP
    var monthlyItemRequests = <?php echo json_encode($monthlyItemRequests); ?>;

        // Prepare data for Chart.js
        var months = [];
        var items = [];
        var requestData = [];

        // Process data
        monthlyItemRequests.forEach(function (data) {
            if (!months.includes(data.month)) {
                months.push(data.month);
            }
            if (!items.includes(data.item_name)) {
                items.push(data.item_name);
            }
        });

        // Initialize requestData array
        for (var i = 0; i < months.length; i++) {
            requestData[i] = [];
            for (var j = 0; j < items.length; j++) {
                requestData[i][j] = 0;
            }
        }

        // Fill requestData with actual values
        monthlyItemRequests.forEach(function (data) {
            var monthIndex = months.indexOf(data.month);
            var itemIndex = items.indexOf(data.item_name);
            requestData[monthIndex][itemIndex] = data.totalRequests;
        });

        // Create the bar chart
        var ctx = document.getElementById('itemRequestsChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: items,
                datasets: []
            },
            options: {
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Item'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Requests'
                        }
                    }
                }
            }
        });

        // Add datasets dynamically
        for (var i = 0; i < months.length; i++) {
            myChart.data.datasets.push({
                label: 'Month ' + months[i],
                data: requestData[i],
                backgroundColor: 'rgba(54, 162, 235, 0.7)', 
                borderColor: 'rgba(54, 162, 235, 1)', 
                borderWidth: 1
            });
        }

        // Update the chart
        myChart.update();

      // barangay data
      // Data fetched from PHP
      var barangayData = <?php echo json_encode($barangayData); ?>;

      // Prepare data for Chart.js
      var labels = [];
      var data = [];
      var totalDistributed = [];
      var totalPending = [];

      // Process data
      barangayData.forEach(function (barangay) {
          labels.push(barangay.brgy_name);
          data.push(barangay.totalRequested);
          totalDistributed.push(barangay.totalDistributed);
          totalPending.push(barangay.totalRequested - barangay.totalDistributed);
      });

      // Create the pie chart
      var ctx = document.getElementById('barangay-pie-chart').getContext('2d');
      var myChart = new Chart(ctx, {
          type: 'pie',
          data: {
              labels: labels,
              datasets: [{
                  data: data,
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 0, 0, 0.7)',         
                    'rgba(0, 128, 0, 0.7)',        
                    'rgba(128, 0, 128, 0.7)'       
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 0, 0, 1)',         
                    'rgba(0, 128, 0, 1)',        
                    'rgba(128, 0, 128, 1)'       
                ],
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              legend: {
                  position: 'right'
              },
              tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label = data.labels[tooltipItem.index];
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        
                        // Use <br> for line breaks
                        return label + ': ' + value + ' total quantity';
                    },
                    footer: function (tooltipItem, data) {
                        var distributed = totalDistributed[tooltipItem[0].index];
                        var pending = totalPending[tooltipItem[0].index];
                        
                        // Use <br> for line breaks
                        return 'Distributed: ' + distributed + ': '+ 'Pending: ' + pending;
                    }
                }
            }
          }
      });
  </script>
</body>
</html>
