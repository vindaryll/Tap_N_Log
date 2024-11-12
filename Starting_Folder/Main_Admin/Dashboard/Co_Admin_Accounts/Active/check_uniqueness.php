<?php

// AJAX function to check if the username and email is unique
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (isset($_POST['value']) && isset($_POST['type'])) {
    $value = trim($_POST['value']);
    $type = $_POST['type']; // 'username' or 'email'
    

    // Prepare SQL based on the type
    if ($type === 'username') {
        $sql = "SELECT COUNT(*) FROM guard_accounts WHERE username = ?";
    } else {
        $sql = "SELECT COUNT(*) FROM guard_accounts WHERE email = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    
    
    // Return JSON response
    echo json_encode(['exists' => $count > 0]);
    
    $stmt->close();
    $conn->close();

}else{
    echo "<script>alert('Invalid request! Please try again.'); window.location.href='main_active.php';</script>";
    exit();
}


