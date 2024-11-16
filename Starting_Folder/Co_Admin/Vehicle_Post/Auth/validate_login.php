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

    // Check if the CAPTCHA answer is correct
    if ($captchaAnswer != $_SESSION['captcha_answer']) {
        echo "<script>alert(\"Incorrect CAPTCHA answer!\"); window.location.href='login.php';</script>";
        exit();
    }

    // Prepare SQL query using bind parameters
    $sql = "SELECT g.*, ga.*
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
