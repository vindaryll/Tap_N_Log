<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = intval(sanitizeInput($_POST['employee_id']));
    $rfid = isset($_POST['rfid']) ? sanitizeInput($_POST['rfid']) : null;

    try {
        $query = $conn->prepare("SELECT employee_rfid FROM employees_profile WHERE employee_id = ?");
        $query->bind_param("i", $employeeId);
        $query->execute();
        $result = $query->get_result();
        $employee = $result->fetch_assoc();

        if ($employee) {
            $assignedRFID = trim($employee['employee_rfid']); // Clean assigned RFID from DB

            if (!empty($assignedRFID)) {
                if ($rfid && $assignedRFID === $rfid) {
                    echo json_encode([
                        'success' => true,
                        'hasRFID' => true,
                        'match' => true,
                        'rfid' => $assignedRFID
                    ]);
                } else {
                    echo json_encode([
                        'success' => true,
                        'hasRFID' => true,
                        'match' => false,
                        'rfid' => $assignedRFID
                    ]);
                }
            } else {
                // Employee has no RFID assigned
                echo json_encode(['success' => true, 'hasRFID' => false]);
            }
        } else {
            // Employee not found
            echo json_encode(['success' => false, 'message' => 'Profile not found.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error occurred.', 'error' => $e->getMessage()]);
    }
}
