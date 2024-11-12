<?php
// Start session
session_start();

// Include database connection
require_once 'C:\xampp\htdocs\TAPNLOG\Database\dbcon.php';

$response = ['success' => false, 'message' => ''];

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Step 1: Check if the session admin ID and new email are set
if (isset($_SESSION['admin_id']) && isset($_POST['nav_newEmail'])) {
    $adminId = $_SESSION['admin_id'];
    $newEmail = sanitizeInput($_POST['nav_newEmail']);
    
    // Validate the email format
    if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Fetch the previous email and username
            $usernameQuery = "SELECT username, email FROM admin_account WHERE admin_id = ?";
            $usernameStmt = $conn->prepare($usernameQuery);
            $usernameStmt->bind_param("i", $adminId);
            $usernameStmt->execute();
            $usernameStmt->bind_result($username, $previousEmail);
            $usernameStmt->fetch();
            $usernameStmt->close();

            // Prepare the SQL update statement
            $updateQuery = "UPDATE admin_account SET email = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $newEmail, $adminId);

            // Execute the update
            if ($stmt->execute()) {
                // Prepare log details
                $logDetails = "Change Email\n\nID: $adminId\nUsername: $username\n\nFrom: $previousEmail\nTo: $newEmail"; 
                $logQuery = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES ('PERSONAL ACCOUNT', ?, 'UPDATE', ?)";
                $logStmt = $conn->prepare($logQuery);
                $logStmt->bind_param("si", $logDetails, $adminId);

                // Execute the log entry
                if ($logStmt->execute()) {
                    // Commit the transaction
                    $conn->commit();
                    $response['success'] = true;
                    $response['message'] = 'Email updated successfully!';
                } else {
                    throw new Exception('Error logging the email change.');
                }
                $logStmt->close();
            } else {
                throw new Exception('Error updating email. Please try again later.');
            }

            $stmt->close();
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $conn->rollback();
            $response['message'] = $e->getMessage();
        }
    } else {
        $response['message'] = 'Invalid email format.';
    }
} else {
    $response['message'] = 'Session expired or email not provided.';
}

// Close the database connection
$conn->close();

// Return response as JSON
echo json_encode($response);
?>
