<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../vendor/plugins/overlayScrollbars/css/OverlayScrollbars.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.css">
</head>
<style>
    .navbar {
      background-color: #fff; 
    }

    .navbar ul {
      list-style: none;
      display: flex;
      align-items: center;
    }

    .navbar li {
      margin-right: 20px;
    }

    .navbar h3 {
      margin: 0;
    }
    .datetime{
      margin-top: 10px;
    }
    .nav-item.active .nav-link {
    color: blue !important; 
  }

  /* Add a hover effect for non-active links if needed */
  .nav-item:not(.active):hover .nav-link {
    color: #555; 
  }
  </style>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="logo">
        <img src="http://localhost/Project/admin/images/HEALTHLOGO.png" alt="Logo" style="max-height: 50px;"> <!-- Adjust the max-height as needed -->
      </li>
      <li class="heading">
        <h3>Medical Consumable Distribution System</h3>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="datetime">
        <p id="datetime"></p>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../admin/images/HEALTHLOGO.png" class="brand-link">
      <img src="../admin/images/HEALTHLOGO.png" alt="MCDS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">MCD System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul id="navLinks" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Dashboard -->
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
           <!-- Inventory Page -->
           <li class="nav-item">
    <a href="supply.php" class="nav-link">
        <i class="nav-icon fas fa-briefcase-medical"></i>
        <p>Inventory</p>
    </a>
</li>
<!-- Request Page -->
<li class="nav-item">
    <a href="insert_request.php" class="nav-link">
        <i class="nav-icon fas fa-file-import"></i> <!-- Updated class name -->
        <p>Insert Request</p>
    </a>
</li>
<li class="nav-item">
    <a href="list_request.php" class="nav-link">
        <i class="nav-icon fas fa-folder-open"></i> <!-- Updated class name -->
        <p>Request List</p>
    </a>
</li>
<li class="nav-item">
    <a href="distribution.php" class="nav-link">
        <i class="nav-icon fas fa-gears"></i> <!-- Updated class name -->
        <p>Distributed list</p>
    </a>
</li>

           <!-- Brgy -->
          <li class="nav-item">
            <a href="bgy.php" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Barangay 
              </p>
            </a>
          </li>
<!--
          <li class="nav-item">
            <a href="tracking.php" class="nav-link">
              <i class="fa-solid fa-file-medical nav-icon"></i>
              <p>Tracking of Items</p>
            </a>
          </li>
-->
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
                <i class="fa-solid fa-sign-out nav-icon"></i>
                <p>Logout</p>
            </a>
        </li>

              <!-- <li class="nav-item">
                <a href="distribution_reports.php" class="nav-link">
                  <i class="fa-solid fa-truck-medical nav-icon"></i>
                  <p>Distribution Reports</p>
                </a>
              </li> -->
          <!-- Reports page -->
          <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../layout/expenditure-budgetary-info.html" class="nav-link">
                  <i class="far fa-regular fa-file nav-icon"></i>
                  <p>Budgetary Information</p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="../forms/total-cost-breakdown.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Total Cost Breakdown</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../forms/budget-allocations.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Budget Allocations</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../forms/variance-analysis.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Variance Analysis</p>
                      <p>Total Monthly Cost - Total Budget Allocation = Variance</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="../layout/statistical-data.html" class="nav-link">
                  <i class="far fa-solid fa-chart-line nav-icon"></i>
                  <p>Statistical Data</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../layout/comparative-analysis.html" class="nav-link">
                  <i class="far fa-solid fa-code-compare  nav-icon"></i>
                  <p>Comparative Analysis</p>
                </a>
              </li>
            </ul>
          </li> -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

  <!-- Add your JavaScript for date/time display here -->
  <script>
     document.addEventListener("DOMContentLoaded", function () {
      // Get the current page URL
      var currentUrl = window.location.href;

      // Select all navigation links
      var navLinks = document.querySelectorAll('#navLinks .nav-link');

      // Loop through each link and check if its href matches the current URL
      for (var i = 0; i < navLinks.length; i++) {
        var link = navLinks[i];

        if (link.href === currentUrl) {
          // Add the "active" class to the matching link
          link.classList.add('active');

          // If you want to highlight the parent list item as well, you can add the following:
          var listItem = link.closest('.nav-item');
          if (listItem) {
            listItem.classList.add('menu-open');
          }
        }
      }
    });
    function updateDateTime() {
      var now = new Date();
      var dateTimeString = now.toLocaleString(); // Customize the date/time format as needed
      document.getElementById('datetime').innerHTML = dateTimeString;
    }

    // Update the date/time every second
    setInterval(updateDateTime, 1000);
   
  </script>
</body>
</html>
