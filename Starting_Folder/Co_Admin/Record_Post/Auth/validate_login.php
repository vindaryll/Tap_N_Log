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

    // Prepare the SQL query with bind parameters
    $sql = "SELECT g.*, ga.*
   FROM guards g
       JOIN guard_accounts ga ON g.guard_id = ga.guard_id
       JOIN stations s ON g.station_id = s.station_id
   WHERE (ga.username = ? OR ga.email = ?)
   AND ga.status = 'ACTIVE'
   AND s.station_id = 1
   LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a record was found
    if ($result->num_rows > 0) {
        // Fetch the user data
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // If the password is correct, set session variables and redirect
            $_SESSION['record_guard_logged'] = true;
            $_SESSION['guard_id'] = $row['guard_id'];
            $_SESSION['station_id'] = $row['station_id'];
            header("Location: ../Dashboard/dashboard_home.php");
            exit();
        } else {
            // Invalid password
            echo "<script>alert(\"Invalid Username or Password!\"); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        // No user found with that username or email
        echo "<script>alert(\"Invalid Username or Password!\");window.location.href='login.php';</script>";
        exit();
    }

    // Close the database connection
    mysqli_close($conn);
} else {

    // Redirect back to login if accessed directly
    header("Location: login.php");
    exit();
}
