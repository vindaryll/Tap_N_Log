<?php

// Start session
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
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
?>