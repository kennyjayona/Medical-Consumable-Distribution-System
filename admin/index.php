<?php
include '../admin/conn.php';

session_start();

$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : '';

if (isset($_POST['submit'])) {
    $user_name = htmlspecialchars($_POST['admin_username']);
    $admin_password = htmlspecialchars($_POST['admin_password']);

    // Retrieve the hashed password from the database
    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE user_name = ?");
    $select_admin->execute([$user_name]);
    $row = $select_admin->fetch(PDO::FETCH_ASSOC);

    if ($select_admin->rowCount() > 0) {
        // Compare the entered password with the hashed password from the database
        if (password_verify($admin_password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header('location: http://localhost/Project/admin/dashboard.php');
            exit();
        } else {
            $message['password'] = 'Incorrect  password!';
        }
    } else {
        $message['user_name'] = 'Incorrect username !';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MCDS | Log in </title>
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
        .login-box {
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

        .login-box {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box .card {
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

        <!-- Right Column (Login Form) -->
        <div class="col-md-6">
            <!-- Login Form -->
            <div class="login-box">
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg"><b>Welcome back Admin!</b></p>
                        <form method="post" action="" id="login-form">
                                    <?php
                                        if (isset($message['user_name'])) {
                                            echo '<span class="error">' . $message['user_name'] . '</span>';
                                        }
                                    ?>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Admin" name="admin_username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                   
                            </div>
                                   
                                    <?php
                                        if (isset($message['password'])) {
                                            echo '<span class="error">' . $message['password'] . '</span>';
                                        }
                                    ?>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" name="admin_password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                                   
                                   
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Log In</button>
                                </div>
                            </div>
                        </form>
                        <p class="mb-0 mt-3">
                            <a href="register.php">Register a new Admin</a>
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
</body>
</html>
