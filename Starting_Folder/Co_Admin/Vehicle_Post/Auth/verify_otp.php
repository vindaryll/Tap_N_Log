<?php
// Start session
session_start();

$response = ['success' => false, 'message' => ''];

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['otpCode'])) {
    $otpCode = sanitizeInput($_POST['otpCode']);

    // Check if the entered OTP matches the one in the session
    if (isset($_SESSION['otp_code']) && $otpCode == $_SESSION['otp_code']) {
        $response['success'] = true;
        $response['message'] = 'OTP verified successfully!';

        // Unset the session otp_code after successful verification
        unset($_SESSION['otp_code']);
        
    } else {
        $response['message'] = 'Invalid OTP code.';
    }
}

// Return response as JSON
echo json_encode($response);
?>
