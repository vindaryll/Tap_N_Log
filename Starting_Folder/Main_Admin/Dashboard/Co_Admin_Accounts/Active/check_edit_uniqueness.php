<?php

// AJAX function to check if the username and email is unique except sa current username ng guard
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';


if (isset($_POST['value']) && isset($_POST['type']) && isset($_POST['guard_id'])) {
    $value = trim($_POST['value']);
    $type = $_POST['type']; // 'username' or 'email'
    $guard_id = intval(trim($_POST['guard_id'])); // Current user's guard ID

    // Prepare SQL based on the type
    if ($type === 'username') {
        $sql = "SELECT COUNT(*) FROM guard_accounts WHERE username = ? AND guard_id != ?";
    } else {
        $sql = "SELECT COUNT(*) FROM guard_accounts WHERE email = ? AND guard_id != ?";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("si", $value, $guard_id); // 'si' for string and integer
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    
    // Return JSON response
    echo json_encode(['exists' => $count > 0]);
    
    $stmt->close();
    $conn->close();

} else {
    echo "<script>alert('Invalid request! Please try again.'); window.location.href='main_active.php';</script>";
    exit();
}
