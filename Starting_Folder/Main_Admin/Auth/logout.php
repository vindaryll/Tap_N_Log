
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

// Include database connection and system log helper
require_once $_SESSION['directory'] . '\Database\dbcon.php';
require_once $_SESSION['directory'] . '\Database\system_log_helper.php';

// Log logout activity
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $username = $_SESSION['username'];

    // Log to system activity log
    logSystemActivity(
        $conn,
        "User logout",
        "SUCCESS",
        "Main Admin logout - ID: $admin_id, Username: $username"
    );
}

// Unset all variables and destroy the session
session_unset();
session_destroy();

// Redirect to login page with SweetAlert
echo "
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
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
            window.location.href = '../../Landing_page/index.php';
        });
    });
</script>";
exit();
?>
