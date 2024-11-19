<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Kapag hindi pa sila nakakalogin
if (!isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

// kapag hindi main admin, redirect sa landing page
if (isset($_SESSION['record_guard_logged']) || isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

// Pending pa

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

    <title>RFID Profiles | Main Admin</title>
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
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Main_Admin\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center px-2">

        <div class="container-fluid row p-0">
            <div class="container col-sm-12">
                <a href="#" class="back-icon" id="backbtn" style="position: absolute;"><i class="bi bi-arrow-left"></i></a>
            </div>

            <div class="container-fluid col-sm-12 mt-sm-1 mt-5">
                <h2 class="text-center w-100">RFID PROFILES</h2>
                <div class="row d-flex justify-content-center align-items-center mt-3">
                    <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="Pendings/main_page.php" class="btn btn-primary w-100 p-3">PENDINGS</a>
                    </div>
                    <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="EMPLOYEES/main_page.php" class="btn btn-primary w-100 p-3">EMPLOYEES</a>
                    </div>
                    <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="CFW/main_page.php" class="btn btn-primary w-100 p-3">CASH FOR WORK STAFF</a>
                    </div>
                    <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="OJT/main_page.php" class="btn btn-primary w-100 p-3">ON THE JOB TRAINEES</a>
                    </div>
                </div>
            </div>

        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#backbtn').on('click', function() {
                window.location.href = '../dashboard_home.php';
            });
        });
    </script>
</body>

</html>