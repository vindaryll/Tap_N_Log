<?php

// Start session
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';


function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_password']) && isset($_POST['confirm_new_password']) && isset($_POST['email_or_username'])) {
        // Sanitize inputs
        $newPassword = sanitizeInput($_POST['new_password']);
        $confirmNewPassword = sanitizeInput($_POST['confirm_new_password']);
        $emailOrUsername = sanitizeInput($_POST['email_or_username']);

        // Validate that the passwords match
        if ($newPassword !== $confirmNewPassword) {
            $response['message'] = 'Passwords do not match. Please try again.';
        } else {
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Retrieve admin_id and username based on email or username
                $userQuery = "SELECT admin_id, username FROM admin_account WHERE email = ? OR username = ?";
                $userStmt = $conn->prepare($userQuery);
                $userStmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
                $userStmt->execute();
                $userResult = $userStmt->get_result();

                if ($userResult->num_rows > 0) {
                    $userData = $userResult->fetch_assoc();
                    $adminId = $userData['admin_id'];
                    $username = $userData['username'];

                    // Hash the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $query = "UPDATE admin_account SET password = ? WHERE admin_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("si", $hashedPassword, $adminId);

                    if ($stmt->execute()) {

                        // Log activity in the admin_activity_log table
                        $logDetails = "Forgot password\n\nId: $adminId\nUsername: $username";
                        $logQuery = "INSERT INTO admin_activity_log (section, details, category) VALUES ('PERSONAL ACCOUNT', ?, 'UPDATE')";
                        $logStmt = $conn->prepare($logQuery);
                        $logStmt->bind_param("s", $logDetails);

                        if ($logStmt->execute()) {
                            // Commit the transaction
                            $conn->commit();
                            $response['success'] = true;
                            $response['message'] = 'Password reset successfully!';
                        } else {
                            throw new Exception('Failed to log activity.');
                        }
                    } else {
                        throw new Exception('Error resetting password.');
                    }
                } else {
                    throw new Exception('User not found.');
                }
            } catch (Exception $e) {
                // Roll back transaction if any part fails
                $conn->rollback();
                $response['message'] = $e->getMessage();
            }
        }
    } else {
        $response['message'] = 'New password, confirmation, and email or username are required.';
    }

    // Return response as JSON
    echo json_encode($response);
    exit();
}
?>
