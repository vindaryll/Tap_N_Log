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

function logActivity($stationId, $coadminId, $station_name, $name, $conn)
{
    $sql = "INSERT INTO activity_log (section, details, category, station_id, guard_id)
            VALUES ('ACCOUNTS', ?, 'LOGS', ?, ?)";
    $stmt = $conn->prepare($sql);
    $details = "Login for Co-Admin\n\nId: " . $coadminId . "\nName: " . $name . "\nStation: " . $station_name;
    $stmt->bind_param("sis", $details, $stationId, $coadminId);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = sanitizeInput($_POST['usernameOrEmail']);
    $password = sanitizeInput($_POST['password']);
    $captchaAnswer = sanitizeInput($_POST['captcha']);

    // Include SweetAlert2 script
    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>";

    // Check if the CAPTCHA answer is correct
    if ($captchaAnswer != $_SESSION['captcha_answer']) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: 'top',
                    title: 'Error!',
                    text: 'Incorrect CAPTCHA answer! Please try again.',
                    icon: 'error',
                    timer: 2000,
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

    $sql = "SELECT g.*, ga.*, s.station_name
            FROM guards g
            JOIN guard_accounts ga ON g.guard_id = ga.guard_id
            JOIN stations s ON g.station_id = s.station_id
            WHERE (ga.username = ? OR ga.email = ?)
            AND ga.status = 'ACTIVE'
            AND s.station_id = 2
            LIMIT 1";

    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any row was returned
    if ($result->num_rows > 0) {
        // Fetch the user data
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // If the password is correct, set session variables and redirect
            $_SESSION['vehicle_guard_logged'] = true;
            $_SESSION['guard_id'] = $row['guard_id'];
            $_SESSION['station_id'] = $row['station_id'];
            $_SESSION['station_name'] = $row['station_name'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['guard_name'];

            // Log the login activity
            logActivity($row['station_id'], $row['guard_id'], $row['station_name'], $row['guard_name'], $conn);

            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: 'top',
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
                        position: 'top',
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
                    position: 'top',
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
                position: 'top',
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