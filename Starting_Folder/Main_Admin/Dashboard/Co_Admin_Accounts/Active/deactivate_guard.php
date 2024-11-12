<?php
// Start session
session_start();

header('Content-Type: application/json'); // Set header for JSON response

// Include database
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_POST['guard_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request! Please try again.']);
    exit();
}

$guardId = intval($_POST['guard_id']);
if ($guardId === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request! Please try again.']);
    exit();
}

// Fetch current guard details
$sql = "SELECT g.guard_name, s.station_name FROM guards g JOIN stations s ON g.station_id = s.station_id WHERE g.guard_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $guardId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $guardData = $result->fetch_assoc();
    $guardName = $guardData['guard_name'];
    $stationName = $guardData['station_name'];

    try {
        // Begin transaction
        $conn->begin_transaction();

        // Update the guard's status to INACTIVE
        $updateSql = "UPDATE guard_accounts SET status = 'INACTIVE' WHERE guard_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $guardId);
        $updateStmt->execute();

        // Insert into activity log
        $logSql = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES (?, ?, ?, ?)";
        $logStmt = $conn->prepare($logSql);

        $section = 'GUARDS';
        $details = "Deactivate Guard\n\nID: $guardId\nStation Name: $stationName\nName: $guardName";
        $category = 'DEACTIVATE';
        $adminId = $_SESSION['admin_id'];

        $logStmt->bind_param("sssi", $section, $details, $category, $adminId);
        $logStmt->execute();

        // Commit transaction
        $conn->commit();

        $updateStmt->close();
        $logStmt->close();

        // Send success response
        echo json_encode(['success' => true, 'message' => 'Guard deactivated successfully!']);
    } catch (Exception $e) {
        // Rollback transaction and send error response
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . addslashes($e->getMessage())]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Guard not found!']);
}

$stmt->close();
$conn->close();
exit();

?>
