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

// Start session
session_start();

// Include database connection and system log helper
require_once $_SESSION['directory'] . '\Database\dbcon.php';
require_once $_SESSION['directory'] . '\Database\system_log_helper.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = sanitizeInput($_POST['usernameOrEmail']);
    $password = sanitizeInput($_POST['password']);
    $captchaAnswer = sanitizeInput($_POST['captcha']);

    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>";

    // Check if the CAPTCHA answer is correct
    if ($captchaAnswer != $_SESSION['captcha_answer']) {
        // Log failed CAPTCHA attempt
        logSystemActivity(
            $conn,
            "Failed login attempt - Invalid CAPTCHA",
            "FAILED",
            "Username/Email attempted: " . $usernameOrEmail
        );

        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: \"top\",
                    title: 'Error!',
                    text: 'Incorrect CAPTCHA answer! Please try again.',
                    icon: 'error',
                    timer: 1500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
        </script>";
        exit();
    }

    $sql = "SELECT * FROM admin_account WHERE username = ? OR email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query was successful and if any row was returned
    if ($result->num_rows > 0) {
        // Fetch the user data
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // If the password is correct, set session variables and redirect
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['username'] = $row['username'];
            
            // Log successful login in system_activity_log
            logSystemActivity(
                $conn,
                "Successful login - Main Admin",
                "SUCCESS",
                "Main Admin login successful - ID: " . $row['admin_id'] . ", Username: " . $row['username']
            );
            
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: \"top\",
                        title: 'Success!',
                        text: 'Login successfully! Redirecting...',
                        icon: 'success',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = '../Dashboard/dashboard_home.php';
                    });
                });
            </script>";
            exit();
        } else {
            // Log invalid password attempt
            logSystemActivity(
                $conn,
                "Failed login attempt - Invalid password",
                "FAILED",
                "Invalid password for Main Admin user: " . $usernameOrEmail
            );

            // Invalid password
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: \"top\",
                        title: 'Error!',
                        text: 'Invalid Username or Password! Please try again.',
                        icon: 'error',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                });
            </script>";
            exit();
        }
    } else {
        // Log user not found
        logSystemActivity(
            $conn,
            "Failed login attempt - Admin not found",
            "FAILED",
            "Main Admin account not found for: " . $usernameOrEmail
        );

        // No user found with that username or email
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: \"top\",
                    title: 'Error!',
                    text: 'Invalid Username or Password!',
                    icon: 'error',
                    timer: 1500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
        </script>";
        exit();
    }

    // Close the database connection
    mysqli_close($conn);
} else {

    // Redirect back to login if accessed directly
    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            position: \"top\",
            title: 'Error!',
            text: 'Unauthorized access!',
            icon: 'error',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            window.location.href = 'login.php';
        });
    });
    </script>";
    exit();
}
?>