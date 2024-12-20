<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Include database connection
require_once $_SESSION['directory'] . '/Database/dbcon.php';

// Function to sanitize input
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get the attendance_id passed from the frontend
$attendance_id = sanitizeInput($_POST['attendance_id'] ?? '');

// Check if the attendance_id is valid
if (empty($attendance_id) || !is_numeric($attendance_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid attendance ID.']);
    exit();
}

// Get cfw_id from attendance record
$query = "SELECT cfw_id, date_att FROM cfw_attendance WHERE cfw_attendance_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $attendance_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cfw_id = $row['cfw_id'];
    $date = $row['date_att'];

    // Check if there's any active attendance for this cfw for that day (is_archived = false)
    $checkQuery = "SELECT * FROM cfw_attendance WHERE cfw_id = ? AND date_att = ? AND is_archived = false LIMIT 1";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("is", $cfw_id, $date);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // If an active attendance exists for that day, return error
        echo json_encode(['success' => false, 'message' => 'This profile has already an active attendance for that day.']);
    } else {
        // If no active attendance for today, proceed with confirmation
        echo json_encode(['success' => true, 'message' => 'No active attendance found. Proceed with confirmation.']);
    }
} else {
    // If no attendance record is found for the given attendance_id
    echo json_encode(['success' => false, 'message' => 'Attendance or profile not found.']);
}

$stmt->close();
$conn->close();
?>
