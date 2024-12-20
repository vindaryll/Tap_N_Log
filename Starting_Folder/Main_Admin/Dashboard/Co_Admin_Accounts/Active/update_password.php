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

// Function to sanitize user inputs
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


// Check if form data has been sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get and sanitize inputs
    $guard_id = intval(sanitizeInput($_POST['guard_id']));
    $newPassword = sanitizeInput($_POST['new_password']);
    $confirmNewPassword = sanitizeInput($_POST['confirm_new_password']);
    $guardName = sanitizeInput($_POST['password_guard_name']);
    
    // Validate that inputs are not empty
    if (empty($guard_id) || empty($newPassword) || empty($confirmNewPassword) || empty($guardName)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all fields.']);
        exit();
    }

    if ($newPassword !== $confirmNewPassword){
        echo json_encode(['success' => false, 'message' => 'Password doesn\'t match']);
        exit();
    }


    try {
        // Hash the new password before inserting it into the database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Begin transaction
        $conn->begin_transaction();

        // Fetch the station name and username associated with the guard
        $detailsSql = "
            SELECT s.station_name, ga.username 
            FROM guards g 
            JOIN stations s ON g.station_id = s.station_id 
            JOIN guard_accounts ga ON g.guard_id = ga.guard_id 
            WHERE g.guard_id = ?
        ";
        $detailsStmt = $conn->prepare($detailsSql);
        $detailsStmt->bind_param("i", $guard_id);
        $detailsStmt->execute();
        $detailsResult = $detailsStmt->get_result();
        $detailsData = $detailsResult->fetch_assoc();

        $stationName = $detailsData['station_name'] ?? 'Unknown Station';
        $username = $detailsData['username'] ?? 'Unknown Username';

        // Update the guard's password
        $updateSql = "UPDATE guard_accounts SET password = ? WHERE guard_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $hashedPassword, $guard_id);
        $updateStmt->execute();

        // Check if rows were affected (guard ID exists and was updated)
        if ($updateStmt->affected_rows > 0) {

            // Insert into activity log
            $logSql = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES (?, ?, ?, ?)";
            $logStmt = $conn->prepare($logSql);   

            $section = 'CO-ADMIN';
            $details = "Update Co-admin Account\n\nId: $guard_id\nStation: $stationName\nName: $guardName\nUsername: $username\n\nChanged password.";
            $category = 'UPDATE';
            $adminId = $_SESSION['admin_id'];

            $logStmt->bind_param("sssi", $section, $details, $category, $adminId);
            $logStmt->execute();

            // Commit transaction
            $conn->commit();

            // Close statements
            $updateStmt->close();
            $logStmt->close();

            // Close the database connection
            $conn->close();

            // Return JSON response for successful update
            echo json_encode([
                'status' => 'success',
                'success' => true,
                'message' => 'Password changed successfully.',
            ]);
            exit();

        } else {
            throw new Exception('No rows were affected. Guard ID may not exist.');
        }
        
    } catch (Exception $e) {

        // Rollback transaction if something failed
        $conn->rollback();

        // Close the database connection
        $conn->close();

        // Return JSON error response
        echo json_encode([
            'status' => 'error',
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
        exit();
    }

}else{
    echo "<script>alert('Invalid request! Please try again.'); window.location.href='main_active.php';</script>";
    exit();
}
?>
