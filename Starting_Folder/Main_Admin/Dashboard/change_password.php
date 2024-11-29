<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nav_new_password']) && isset($_POST['confirm_new_password']) && isset($_POST['nav_current_password'])) {
        // Sanitize inputs
        $newPassword = sanitizeInput($_POST['nav_new_password']);
        $confirmNewPassword = sanitizeInput($_POST['confirm_new_password']);
        $currentPassword = sanitizeInput($_POST['nav_current_password']);
        $adminId = $_SESSION['admin_id']; // Admin ID from the session

        // Validate that the passwords match
        if ($newPassword !== $confirmNewPassword) {
            $response['message'] = 'Passwords do not match. Please try again.';
        } else {
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Retrieve the username and current hashed password from the database
                $query = "SELECT username, password FROM admin_account WHERE admin_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $adminId);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($username, $hashedCurrentPassword);
                    $stmt->fetch();

                    // Verify the current password
                    if (password_verify($currentPassword, $hashedCurrentPassword)) {
                        // Hash the new password
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                        // Update the password in the database
                        $updateQuery = "UPDATE admin_account SET password = ? WHERE admin_id = ?";
                        $updateStmt = $conn->prepare($updateQuery);
                        $updateStmt->bind_param("si", $hashedPassword, $adminId);

                        if ($updateStmt->execute()) {
                            // Log activity in the admin_activity_log table
                            $logDetails = "Change password\n\nId: $adminId\nUsername: $username";
                            $logQuery = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES ('PERSONAL ACCOUNT', ?, 'UPDATE', ?)";
                            $logStmt = $conn->prepare($logQuery);
                            $logStmt->bind_param("si", $logDetails, $adminId);

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
                        // Current password is incorrect; return an error without starting the transaction
                        $response['message'] = 'Current password is incorrect.';
                    }
                } else {
                    $response['message'] = 'No account found with provided credentials.';
                }
            } catch (Exception $e) {
                // Roll back transaction if any part fails (if it was started)
                if ($conn->in_transaction()) {
                    $conn->rollback();
                }
                $response['message'] = $e->getMessage();
            }
        }
    } else {
        $response['message'] = 'New password, confirmation, and current password are required.';
    }

    // Return response as JSON
    echo json_encode($response);
    exit();
}
?>
