<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

header('Content-Type: application/json'); // Set header for JSON response

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

// Fetch current guard details, including username
$sql = "
    SELECT g.guard_name, s.station_name, ga.username 
    FROM guards g 
    JOIN stations s ON g.station_id = s.station_id 
    JOIN guard_accounts ga ON g.guard_id = ga.guard_id 
    WHERE g.guard_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $guardId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $guardData = $result->fetch_assoc();
    $guardName = $guardData['guard_name'];
    $stationName = $guardData['station_name'];
    $username = $guardData['username'];

    try {
        // Begin transaction
        $conn->begin_transaction();

        // Update the guard's status to ACTIVE
        $updateSql = "UPDATE guard_accounts SET status = 'ACTIVE' WHERE guard_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $guardId);
        $updateStmt->execute();

        // Insert into activity log
        $logSql = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES (?, ?, ?, ?)";
        $logStmt = $conn->prepare($logSql);

        $section = 'CO-ADMIN';
        $details = "Reactivate Co-admin Account\n\nID: $guardId\nStation: $stationName\nName: $guardName\nUsername: $username";
        $category = 'REACTIVATE';
        $adminId = $_SESSION['admin_id'];

        $logStmt->bind_param("sssi", $section, $details, $category, $adminId);
        $logStmt->execute();

        // Commit transaction
        $conn->commit();

        // Close statements
        $updateStmt->close();
        $logStmt->close();

        // Send success response
        echo json_encode(['success' => true, 'message' => 'Account reactivated successfully.']);
    } catch (Exception $e) {
        // Rollback transaction if something failed
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