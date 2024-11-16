<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = intval(sanitizeInput($_POST['employee_id']));
    $rfid = (isset($_POST['rfid']) && !empty($_POST['rfid']) && $_POST['rfid'] !== '') ? sanitizeInput($_POST['rfid']) : null;
    $adminId = $_SESSION['admin_id']; // Assuming this is set for the logged-in admin

    if (empty($employeeId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid employee ID.']);
        exit();
    }

    try {
        $conn->begin_transaction();

        // Fetch current employee details
        $query = $conn->prepare("SELECT first_name, last_name, status FROM employees_profile WHERE employee_id = ?");
        $query->bind_param("i", $employeeId);
        $query->execute();
        $result = $query->get_result();
        $employee = $result->fetch_assoc();

        if (!$employee) {
            echo json_encode(['success' => false, 'message' => 'Employee not found.']);
            exit();
        }

        if ($employee['status'] === 'ACTIVE') {
            echo json_encode(['success' => false, 'message' => 'Employee is already active.']);
            exit();
        }

        // Update the status to ACTIVE and assign RFID if provided
        $updateQuery = $conn->prepare("UPDATE employees_profile SET status = 'ACTIVE', employee_rfid = ? WHERE employee_id = ?");
        $updateQuery->bind_param("si", $rfid, $employeeId);
        $updateQuery->execute();

        // Log the reactivation
        $fullName = $employee['first_name'] . ' ' . $employee['last_name'];
        $rfidText = $rfid ? $rfid : 'None';
        $logDetails = "Reactivate Employee Profile\n\nId: $employeeId\nName: $fullName\nRFID: $rfidText";

        $logQuery = $conn->prepare("INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES ('RFID', ?, 'REACTIVATE', ?)");
        $logQuery->bind_param("si", $logDetails, $adminId);
        $logQuery->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Employee reactivated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
