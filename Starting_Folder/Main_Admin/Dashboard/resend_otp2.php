<?php
// Start session
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Access PHPMailer
require $_SESSION['directory'] . '\PHPMailer\src\Exception.php';
require $_SESSION['directory'] . '\PHPMailer\src\PHPMailer.php';
require $_SESSION['directory'] . '\PHPMailer\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['success' => false, 'message' => ''];

// Function to mask email for security
function maskEmail($email) {
    $parts = explode('@', $email);
    if (count($parts) == 2) {
        $name = $parts[0];
        $domain = $parts[1];

        // Mask the name part
        $maskedName = '';
        for ($i = 0; $i < strlen($name); $i++) {
            $maskedName .= ($i < 3) ? $name[$i] : '*';
        }

        // Mask the domain part
        $maskedDomain = '';
        for ($i = 0; $i < strlen($domain); $i++) {
            if ($i == 0 || $i == strlen($domain) - 1) {
                $maskedDomain .= $domain[$i];
            } else {
                $maskedDomain .= '*';
            }
        }

        return $maskedName . '@' . $maskedDomain;
    }
    return $email;
}

// Check if email and OTP are set in the session
if (isset($_POST['email']) && isset($_SESSION['otp_code'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if ($email) {
        $otpCode = $_SESSION['otp_code']; // Use existing OTP code

        // Setup PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tapnlog.official@gmail.com'; // SMTP username
            $mail->Password = 'yulpryuuhvuttgdv'; // SMTP password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Set email properties
            $mail->setFrom('no-reply@gmail.com', 'Tap_N_Log');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'OTP Code for New Email Verification';

            // Email body with OTP styling
            $mail->Body = '
                <div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2 style="color: #007bff;">Tap_N_Log</h2>
                        <p style="font-size: 18px; color: #555;">New Email Verification</p>
                    </div>
                    <div style="padding: 20px; background-color: #ffffff; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <p style="color: #555;">Hello, Admin!</p>
                        <p style="color: #555;">To confirm your new email, please use the OTP code below:</p>
                        <h3 style="color: #28a745; text-align: center;">Your OTP code is: <b>' . $otpCode . '</b></h3>
                        <p style="color: #555;">If you did not request this change, please ignore this email.</p>
                        <p style="color: #777;">Thank you,<br>The Tap_N_Log Team</p>
                    </div>
                </div>
            ';

            $mail->send();

            // Mask the email for response
            $maskedEmail = maskEmail($email);
            $response['success'] = true;
            $response['message'] = 'OTP code has been resent to ' . $maskedEmail . '! Check your email now.';
        } catch (Exception $e) {
            $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        $response['message'] = 'Invalid email address.';
    }
} else {
    $response['message'] = 'Email is required, or OTP code is missing from the session.';
}

// Return response as JSON
echo json_encode($response);
?>
