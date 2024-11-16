<?php
// Backend to check if the profile for deactivation has an rfid or none

session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = intval(sanitizeInput($_POST['employee_id']));

    $query = $conn->prepare("SELECT employee_rfid FROM employees_profile WHERE employee_id = ?");
    $query->bind_param("i", $employeeId);
    $query->execute();
    $result = $query->get_result();
    $employee = $result->fetch_assoc();

    if ($employee && !empty($employee['employee_rfid'])) {
        echo json_encode(['hasRFID' => true]);
    } else {
        echo json_encode(['hasRFID' => false]);
    }
}
