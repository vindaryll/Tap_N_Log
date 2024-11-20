
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

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = sanitizeInput($_POST['usernameOrEmail']);
    $password = sanitizeInput($_POST['password']);
    $captchaAnswer = sanitizeInput($_POST['captcha']);

    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>";

    // Check if the CAPTCHA answer is correct
    if ($captchaAnswer != $_SESSION['captcha_answer']) {
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

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success!',
            text: 'Login successful. Redirecting...',
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
</script>"; -->