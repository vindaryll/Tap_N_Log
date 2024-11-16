<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Get the RFID and employee ID from the POST request
$rfid = trim($_POST['rfid']);
$currentEmployeeId = trim($_POST['employee_id']); // ID of the employee being edited

// Return valid if the RFID is empty
if (empty($rfid)) {
    echo json_encode(['success' => true, 'exists' => false, 'message' => 'RFID is valid.']);
    exit();
}

try {
    // Check if the RFID exists in the table excluding the current employee's record
    $sql = "SELECT COUNT(*) AS count FROM employees_profile WHERE employee_rfid = ? AND employee_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $rfid, $currentEmployeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // RFID already exists
        echo json_encode(['success' => true, 'exists' => true, 'message' => 'RFID already exists.']);
    } else {
        // RFID is available
        echo json_encode(['success' => true, 'exists' => false, 'message' => 'RFID is available.']);
    }
} catch (Exception $e) {
    // Handle any errors
    echo json_encode(['success' => false, 'message' => 'An error occurred while checking RFID.', 'error' => $e->getMessage()]);
}

?>
