<?php

session_start();

// Kapag hindi pa sila nakakalogin, dederetso sa login page
if (!isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: ../../../Landing_page/index.php");
    exit();
}

// kapag hindi belong sa Record Post, redirect sa landing page
if (isset($_SESSION['record_guard_logged']) || isset($_SESSION['admin_logged'])) {
    header("Location: ../../../Landing_page/index.php");
    exit();
}


echo ("This is the dashboard of Vehicle Post");
// STANDBY PA LANG
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>Vehicle Post Dashboard</title>
    </head>
    <body>
        <br><br>
        <button type="button" id="logoutButton" class="btn btn-primary">Logout</button>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        
        <script>

            $(document).ready(function() {
                // Confirmation for logout
                $('#logoutButton').click(function() {
                    if (confirm("Are you sure you want to log out?")) {
                        window.location.href = "../Auth/logout.php";
                    }
                });
            });
            
        </script>
    </body>
</html>