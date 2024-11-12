<?php

// Start session
session_start();

// Include database connection
require_once 'C:\xampp\htdocs\TAPNLOG\Database\dbcon.php';

// Access PHPMailer
require 'C:\xampp\htdocs\TAPNLOG\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\TAPNLOG\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\TAPNLOG\PHPMailer\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['success' => false, 'message' => ''];

function maskEmail($email) {
    $parts = explode('@', $email);
    if (count($parts) == 2) {
        $name = $parts[0];
        $domain = $parts[1];

        // Mask the name
        $maskedName = '';
        for ($i = 0; $i < strlen($name); $i++) {
            $maskedName .= ($i < 3) ? $name[$i] : '*';
        }

        // Mask the domain
        $maskedDomain = '';
        for ($i = 0; $i < strlen($domain); $i++) {
            $maskedDomain .= ($i == 0 || $i == strlen($domain) - 1) ? $domain[$i] : '*';
        }

        return $maskedName . '@' . $maskedDomain;
    }
    return $email;
}

if (isset($_SESSION['otp_code']) && isset($_SESSION['admin_id'])) {
    $adminId = $_SESSION['admin_id'];

    // Fetch the user's email based on admin_id from the database
    $query = "SELECT email FROM admin_account WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $email = $user['email'];
        
        // Use the existing OTP code in the session
        $otpCode = $_SESSION['otp_code'];

        // Send OTP via email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tapnlog.official@gmail.com';
            $mail->Password = 'yulpryuuhvuttgdv';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Email settings
            $mail->setFrom('no-reply@gmail.com', 'Tap_N_Log');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'OTP Code for Verification';
            $mail->Body = '
                <div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2 style="color: #007bff;">Tap_N_Log</h2>
                        <p style="font-size: 18px; color: #555;">Password Reset Request</p>
                    </div>
                    <div style="padding: 20px; background-color: #ffffff; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <p style="color: #555;">Hello, Admin!</p>
                        <p style="color: #555;">Did you request to reset your password? If so, please use the OTP below to proceed with the reset:</p>
                        <h3 style="color: #28a745; text-align: center;">Your OTP code is: <b>' . $otpCode . '</b></h3>
                        <p style="color: #555;">If you did not request a password reset, please disregard this email.</p>
                        <p style="color: #777;">Thank you,<br>The Tap_N_Log Team</p>
                    </div>
                </div>
            ';

            $mail->send();

            // Mask the email for the response
            $maskedEmail = maskEmail($email);

            $response['success'] = true;
            $response['message'] = 'OTP code successfully resent to '. $maskedEmail .'! Check your email now.';
        } catch (Exception $e) {
            $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        $response['message'] = 'Admin account not found.';
    }
} else {
    $response['message'] = 'OTP code or admin ID is not set.';
}

// Return response as JSON
echo json_encode($response);
?>
