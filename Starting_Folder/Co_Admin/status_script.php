<script>
    // Function to check the guard's status
    function checkGuardStatus() {
        $.ajax({
            url: '/TAPNLOG/Starting_Folder/Co_Admin/check_status.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status === 'INACTIVE') {
                    // Display SweetAlert if guard is inactive
                    Swal.fire({
                        position: 'top',
                        title: 'Session Terminated',
                        text: 'Your session has been terminated. Please log in again.',
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Redirect to logout page
                        window.location.href = '/TAPNLOG/Starting_Folder/Co_Admin/logout.php';
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking guard status:', error);
                console.log(xhr.responseText); // Log the full response text to inspect the error
            }
        });
    }

    // Set interval to check guard status every 10 seconds
    setInterval(checkGuardStatus, 10000); // Check every 10 seconds
</script>