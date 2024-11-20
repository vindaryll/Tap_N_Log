<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_SESSION['record_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

if (isset($_SESSION['vehicle_guard_logged']) || isset($_SESSION['admin_logged'])) {
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

    <title>Attendance Log - Employees | Co-Admin for Record Post</title>
    <style>
        /* BACK BUTTON */
        .back-icon {
            color: #1877f2;
            font-size: 2rem;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.3s;
        }

        .back-icon:hover {
            color: #145dbf;
            transform: scale(1.1);
        }

        /* END FOR BACK BUTTON */
    </style>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\Record_Post\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center px-2">

        <div class="container-fluid row p-0">
            <div class="container col-sm-12">
                <a href="#" class="back-icon" id="backbtn" style="position: absolute;"><i class="bi bi-arrow-left"></i></a>
            </div>

            <div class="container-fluid col-sm-12 mt-sm-1 mt-5">
                <h2 class="text-center w-100">ATTENDANCE LOG - EMPLOYEES</h2>
                <div class="row d-flex justify-content-center align-items-center mt-3">
                    <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="Auto_Time_In/main_page.php" class="btn btn-primary w-100 p-3">RFID SCAN FOR TIME-IN</a>
                    </div>
                    <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="Auto_Time_Out/main_page.php" class="btn btn-primary w-100 p-3">RFID SCAN FOR TIME-OUT</a>
                    </div>
                    <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="Manual_Time_In/main_page.php" class="btn btn-primary w-100 p-3">MANUAL TIME-IN</a>
                    </div>
                    <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="Manual_Time_Out/main_page.php" class="btn btn-primary w-100 p-3">MANUAL TIME-OUT</a>
                    </div>
                </div>
            </div>

        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#backbtn').on('click', function() {
                window.location.href = '../main_page.php';
            });
        });
    </script>
</body>

</html>