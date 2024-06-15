<?php
include 'conn.php';

session_start();

$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : '';
$message = []; // Initialize an array to store error messages

if (isset($_POST['submit'])) {
    $user_name = htmlspecialchars($_POST['admin_username']);
    $admin_password = htmlspecialchars($_POST['admin_password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    try {
        // Check for duplicate username
        $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE user_name = ?");
        $select_admin->execute([$user_name]);

        if ($select_admin->rowCount() > 0) {
            $message['user_name'] = 'Username already exists!';
        } else {
            if ($admin_password !== $confirm_password) {
                $message['confirm_password'] = 'Confirm password not matched!';
            } else {
                // Hash the password
                $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

               // Insert into the 'admin' table
               $insert_admin = $conn->prepare("INSERT INTO `admin` (user_name, password) VALUES (?, ?)");
               $insert_admin->execute([$user_name, $hashed_password]);

               if ($insert_admin->rowCount() > 0) {
                   $_SESSION['admin_id'] = $conn->lastInsertId(); // Use lastInsertId to get the ID of the inserted record
                   header('location:index.php');
                   exit();
               } else {
                   $message['general'] = 'Error inserting password.';
               }
           }
       }
   } catch (PDOException $e) {
       $message['general'] = 'Error: ' . $e->getMessage();
   }

   // Output debug information
//    echo "<pre>";
//    echo "Form Data:\n";
//    var_dump($_POST);
//    echo "Error Messages:\n";
//    var_dump($message);
//    echo "</pre>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MCDS | Register</title>
   <!-- Favicon -->
   <link rel="icon" href="../admin/images/HEALTHLOGO.png" type="image/x-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../vendor/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.min.css">
 
  <style>
    body {
      background-image: url('../admin/images/intro.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      max-width: 800px; 
      background-color: #fffdfd; 
      border-radius: 10px;
      overflow: hidden; 
      padding: 0px; 
    }

    .side-image,
    .register-box {
      width: 100%;
    }

    .side-image img {
      width: 100%;
      height: auto;
      border-radius: 10px; 
      box-shadow: none; 
    }

    .side-image .card {
      height: 100%; 
    }

    .register-box {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .register-box .card {
      background: transparent;
      border: none;
      box-shadow: none;
      border-radius: 10px; 
      margin-right: 10px;
      margin-top: 80px;
    }

    .input-group-text i {
    cursor: pointer;
  }
  .error {
            font-size: 12px;
            color: #cc0000; 
            margin-bottom: 10px;
        }
  </style>
</head>
<body class="hold-transition login-page">
    <div class="container">
        <div class="row">
            <!-- Left Column (Side Image) -->
            <div class="col-md-6 side-image">
                <div class="card">
                    <img src="../admin/images/bg.png" alt="Form Image" class="img-fluid rounded">
                </div>
            </div>

            <!-- Right Column (Register Form) -->
            <div class="col-md-6">
                <!-- Register Form -->
                <div class="register-box">
                    <div class="card">
                        <div class="card-body login-card-body">
                            <p class="login-box-msg"><b>Register a new Admin</b></p>
                            <form method="post" action="" id="register-form">
                                <?php
                                    if (isset($message['user_name'])) {
                                        echo '<span class="error">' . $message['user_name'] . '</span>';
                                    }

                                 ?>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Username" name="admin_username">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="Password" name="admin_password" id="register-password" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                         <span class="fas fa-lock" id="show-register-password"></span>
                                        </div>
                                    </div>
                                </div>
                                    <?php
                                        if (isset($message['confirm_password'])) {
                                            echo '<span class="error">' . $message['confirm_password'] . '</span>';
                                        }
                                    ?>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirm-password" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                         <span class="fas fa-lock" id="show-confirm-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-block" name="submit">Register</button>
                                       

                                    </div>
                                </div>
                            </form>
                            <p class="mb-0 mt-3">
                                <a href="index.php">Already have an account? Log In</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <!-- jQuery -->
  <script src="../vendor/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../vendor/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../vendor/dist/js/adminlte.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to toggle password visibility
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            // Check if the elements exist
            if (passwordInput && icon) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                icon.classList.toggle('fa-lock-open');
                icon.classList.toggle('fa-lock');
            }
        }

        // Attach event listeners if the elements exist
        const showRegisterPassword = document.getElementById('show-register-password');
        if (showRegisterPassword) {
            showRegisterPassword.addEventListener('click', function () {
                togglePasswordVisibility('register-password', 'show-register-password');
            });
        }

        const showConfirmPassword = document.getElementById('show-confirm-password');
        if (showConfirmPassword) {
            showConfirmPassword.addEventListener('click', function () {
                togglePasswordVisibility('confirm-password', 'show-confirm-password');
            });
        }
    });
</script>

</body>
</html>
