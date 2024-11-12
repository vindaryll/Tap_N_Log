<?php
// Start session
session_start();

// Include database connection
require_once 'C:\xampp\htdocs\TAPNLOG\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = sanitizeInput($_POST['usernameOrEmail']);
    $password = sanitizeInput($_POST['password']);
    $captchaAnswer = sanitizeInput($_POST['captcha']);
    
    $sql = "SELECT g.*, ga.*
            FROM guards g
                JOIN guard_accounts ga ON g.guard_id = ga.guard_id
                JOIN stations s ON g.station_id = s.station_id
            WHERE (ga.username = '$usernameOrEmail' OR ga.email = '$usernameOrEmail')
            AND ga.status = 'ACTIVE'
            AND s.station_id = 1
            LIMIT 1";
    $result = mysqli_query($conn, $sql);


    // Check if the query was successful and if any row was returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch the user data
        $row = mysqli_fetch_assoc($result);
        
        // Verify the password (assuming you have hashed passwords stored) $password == $user['password']
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

    
}else {

    // Redirect back to login if accessed directly
    header("Location: login.php");
    exit();
}



?>