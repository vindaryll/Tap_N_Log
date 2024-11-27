<?php

session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// If they haven't logged in yet
if (!isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

if (isset($_SESSION['record_guard_logged']) || isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Jquery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <title>Main Admin Dashboard</title>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once 'C:\xampp\htdocs\TAPNLOG\Starting_Folder\Main_Admin\Dashboard\navbar.php'; ?>

    <div class="container">
        <div class="row d-flex justify-content-center align-items-center mt-3">
            <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                <a href="RFID/main_page.php" class="btn btn-primary w-100 p-3">RFID USERS</a>
            </div>
            <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                <a href="Co_Admin_Accounts/Active/main_active.php" class="btn btn-primary w-100 p-3">CO-ADMIN ACCOUNTS</a>
            </div>
            <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                <a href="Records/main_page.php" class="btn btn-primary w-100 p-3">RECORDS</a>
            </div>
            <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                <a href="" class="btn btn-primary w-100 p-3">ACTIVITY LOGS</a>
            </div>
        </div>
    </div>

    <br><br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>