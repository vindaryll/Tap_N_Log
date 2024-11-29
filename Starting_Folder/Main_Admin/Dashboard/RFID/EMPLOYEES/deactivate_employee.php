<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = intval(sanitizeInput($_POST['employee_id']));
    $type = sanitizeInput($_POST['type']); // "returned", "lost", or "no_rfid"
    $rfid = isset($_POST['rfid']) ? sanitizeInput($_POST['rfid']) : null;
    $adminId = $_SESSION['admin_id'];

    try {
        $conn->begin_transaction();

        // Fetch employee details
        $query = $conn->prepare("SELECT first_name, last_name, employee_rfid, status FROM employees_profile WHERE employee_id = ?");
        $query->bind_param("i", $employeeId);
        $query->execute();
        $result = $query->get_result();
        $employee = $result->fetch_assoc();

        if (!$employee) {
            echo json_encode(['success' => false, 'message' => 'Profile not found.']);
            exit();
        }

        if ($employee['status'] === 'INACTIVE') {
            echo json_encode(['success' => false, 'message' => 'Profile is already inactive.']);
            exit();
        }

        $logDetails = "Deactivate Employee Profile\n\nId: $employeeId\nName: {$employee['first_name']} {$employee['last_name']}\n";

        // Log based on type
        if ($type === 'returned') {
            $logDetails .= "Returned RFID: $rfid";
        } elseif ($type === 'lost') {
            $logDetails .= "Lost RFID: {$employee['employee_rfid']}";
        } else {
            $logDetails .= "No RFID";
        }

        // Deactivate the employee and set RFID to NULL
        $updateQuery = $conn->prepare("UPDATE employees_profile SET status = 'INACTIVE', employee_rfid = NULL WHERE employee_id = ?");
        $updateQuery->bind_param("i", $employeeId);
        $updateQuery->execute();

        // Log the activity
        $logQuery = $conn->prepare("INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES ('RFID', ?, 'DEACTIVATE', ?)");
        $logQuery->bind_param("si", $logDetails, $adminId);
        $logQuery->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Profile deactivated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
