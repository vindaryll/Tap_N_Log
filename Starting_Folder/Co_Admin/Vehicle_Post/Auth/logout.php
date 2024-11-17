<?php
session_start();

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
            window.location.href = '../../Landing_page/index.php';
        });
    });
</script>";
exit();
?>
