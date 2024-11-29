<?php

session_start();

// Include database connection and system log helper
require_once $_SESSION['directory'] . '\Database\dbcon.php';
require_once $_SESSION['directory'] . '\Database\system_log_helper.php';

$response = ['success' => false, 'message' => ''];

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['otpCode'])) {
    $otpCode = sanitizeInput($_POST['otpCode']);

    // Check if the entered OTP matches the one in the session
    if (isset($_SESSION['otp_code']) && $otpCode == $_SESSION['otp_code']) {
        // Log successful OTP verification
        logSystemActivity(
            $conn,
            "OTP verification",
            "SUCCESS",
            "OTP verified successfully for Main Admin password reset"
        );

        $response['success'] = true;
        $response['message'] = 'OTP verified successfully!';

        // Unset the session otp_code after successful verification
        unset($_SESSION['otp_code']);
        
    } else {
        // Log failed OTP verification
        logSystemActivity(
            $conn,
            "OTP verification failed",
            "FAILED",
            "Invalid OTP entered for Main Admin: $otpCode"
        );

        $response['message'] = 'Invalid OTP code.';
    }
} else {
    // Log missing OTP code
    logSystemActivity(
        $conn,
        "OTP verification attempt",
        "FAILED",
        "Missing OTP code in Main Admin verification request"
    );

    $response['message'] = 'OTP code is required.';
}

// Return response as JSON
echo json_encode($response);
?>
