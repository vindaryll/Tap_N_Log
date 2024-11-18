<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Kapag hindi pa sila nakakalogin, dederetso sa login page
if (!isset($_SESSION['record_guard_logged'])) {
    header("Location: ../../../Landing_page/index.php");
    exit();
}

// kapag hindi belong sa Record Post, redirect sa landing page
if (isset($_SESSION['vehicle_guard_logged']) || isset($_SESSION['admin_logged'])) {
    header("Location: ../../Landing_page/index.php");
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

    <title>Home | Record Post</title>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\Record_Post\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center">

        <div class="container-fluid row col-sm-12">

            <div class="container col-sm-12 mb-3">
                <button type="button" class="btn btn-primary" id="backbtn">Back</button>
            </div>

            <div class="container">
                <div class="row d-flex justify-content-center align-items-center mt-3">
                    <div class="col-md-4 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="" class="btn btn-primary w-100 p-3">LOGBOOKS/ATTENDANCE</a>
                    </div>
                    <div class="col-md-4 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="" class="btn btn-primary w-100 p-3">RECORDS</a>
                    </div>
                    <div class="col-md-4 col-sm-12 d-flex justify-content-center align-items-center mb-3">
                        <a href="" class="btn btn-primary w-100 p-3">ACTIVITY LOGS</a>
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