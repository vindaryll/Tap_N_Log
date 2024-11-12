<?php


// GAWAN NG UI, and mahalaga lang rito ay yung href ng <a> tags, kayo na ang bahala kung button ang gagamitin niyo or a tags pa rin

session_start();

// to get the directory path for require purposes
if (!isset($_SESSION['directory']) || !isset($_SESSION['ip_address'])){
    header("Location: /tapnlog/index.php");
    exit();
}


// if already logged in, pupunta na sa kani-kanilang dashboard
if (isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: /tapnlog/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/dashboard_home.php");
    exit();
}

if (isset($_SESSION['record_guard_logged'])) {
    header("Location: /tapnlog/Starting_Folder/Co_Admin/Record_Post/Dashboard/dashboard_home.php");
    exit();
}

if (isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Main_Admin/Dashboard/dashboard_home.php");
    exit();
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Jquery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <title>Landing Page</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome to the Landing Page</h2>
        <p>Please select an option:</p>

        <div class="btn-group-vertical d-flex" role="group" aria-label="Button group">
            <a href="../Co_Admin/Vehicle_Post/Auth/login.php" class="btn btn-primary">Vehicle Post</a>
            <a href="../Co_Admin/Record_Post/Auth/login.php" class="btn btn-success">Record Post</a>
            <a href="../Main_Admin/Auth/login.php" class="btn btn-info">Main Admin</a>
            <a href="../RFID_Registration/main_page.php" class="btn btn-danger">Apply for RFID</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
