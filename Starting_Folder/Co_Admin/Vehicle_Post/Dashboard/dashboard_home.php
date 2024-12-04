<?php

session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// If already logged in, redirect to dashboard
if (!isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

if (isset($_SESSION['admin_logged']) || isset($_SESSION['record_guard_logged'])) {
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

    <!-- Real time session checker -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\status_script.php'; ?>

    <title>Home | Vehicle Post</title>
    <style>
        /* BUTTON CONTAINER */
        #main-container {
            height: calc(100vh - 87px);
            width: 100%;
            display: flex;
            justify-content: center;
        }

        #button-cont {
            position: absolute;
            top: 30%;
            width: 70%;
            border-radius: 20px;
        }

        @media (max-width: 768px) {

            #button-cont {
                top: 30%;
                width: 90%;
            }
        }
    </style>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\Vehicle_Post\Dashboard\navbar_2.php'; ?>

    <!-- START OF CONTAINER -->
    <div id="main-container" class="w-100 p-0 m-0">
        <div class="row w-100 justify-content-center text-center">
            <h1 class="page-title">Welcome back, <?php echo $_SESSION['username'] ?>!</h1>
        </div>
        <div id="button-cont" class="row d-flex justify-content-center align-items-center glass p-md-5 m-1">
            <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center my-md-4 my-2">
                <a href="Vehicle_Log/main_page.php" class="btn btn-primary btn-custom py-4 w-100 px-2">VEHICLE LOG</a>
            </div>
            <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center my-md-4 my-2">
                <a href="Records/main_page.php" class="btn btn-primary btn-custom py-4 w-100 px-2">RECORDS</a>
            </div>
            <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center my-md-4 my-2">
                <a href="Activity_Logs/main_page.php" class="btn btn-primary btn-custom py-4 w-100 px-2">ACTIVITY LOGS</a>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>