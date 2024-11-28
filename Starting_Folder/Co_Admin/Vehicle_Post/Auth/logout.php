
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title></title>
</head>
<body>
    
</body>
</html>


<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Log logout activity
if (isset($_SESSION['guard_id'], $_SESSION['station_id'])) {
    $guard_id = $_SESSION['guard_id'];
    $station_id = $_SESSION['station_id'];
    $guard_name = $_SESSION['name'];
    $station_name = $_SESSION['station_name'];

    // Prepare the activity details for logging
    $details = "Logout for Co-Admin\n\nId: $guard_id\nName: $guard_name\nStation: $station_name";

    // SQL to insert the activity log
    $sql = "INSERT INTO activity_log (section, details, category, station_id, guard_id)
               VALUES ('ACCOUNTS', ?, 'LOGS', ?, ?)";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $details, $station_id, $guard_id);
    $stmt->execute();
}

// Unset all variables and destroy the session
session_unset();
session_destroy();

// Include SweetAlert2 script
echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>";

// Show SweetAlert2 notification
echo "
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            position: 'top',
            title: 'Success!',
            text: 'Logout successfully! Redirecting...',
            icon: 'success',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            window.location.href = '../../../Landing_page/index.php';
        });
    });
</script>";
exit();
?>
