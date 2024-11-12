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
    if (isset($_POST['new_password'], $_POST['confirm_new_password'], $_POST['email_or_username'])) {
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
                // Retrieve guard_id, username, email, guard_name, and station_name based on email or username
                $userQuery = "
                    SELECT g.guard_id, ga.email, g.station_id, g.guard_name, s.station_name
                    FROM guard_accounts ga
                    INNER JOIN guards g ON ga.guard_id = g.guard_id
                    INNER JOIN stations s ON g.station_id = s.station_id
                    WHERE (ga.email = ? OR ga.username = ?) AND g.station_id = 1
                ";
                
                $userStmt = $conn->prepare($userQuery);
                $userStmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
                $userStmt->execute();
                $userResult = $userStmt->get_result();

                if ($userResult->num_rows > 0) {
                    $userData = $userResult->fetch_assoc();
                    $guardId = $userData['guard_id'];
                    $guardName = $userData['guard_name'];
                    $stationName = $userData['station_name'];
                    $stationId = $userData['station_id'];

                    // Hash the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the password in the guard_accounts table
                    $updateQuery = "UPDATE guard_accounts SET password = ? WHERE guard_id = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("si", $hashedPassword, $guardId);


                    if ($updateStmt->execute()) {
                        // Log activity in the activity_log table
                        $logDetails = "Forgot password for guard\n\nGuard ID: $guardId\nGuard Name: $guardName\nStation Name: $stationName";
                        $logQuery = "INSERT INTO activity_log (section, details, category, station_id) VALUES ('ACCOUNTS', ?, 'UPDATE', ?)";
                        $logStmt = $conn->prepare($logQuery);
                        $logStmt->bind_param("si", $logDetails, $stationId);

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
