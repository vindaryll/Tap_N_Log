<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = intval(sanitizeInput($_POST['employee_id']));
    $rfid = sanitizeInput($_POST['rfid']);

    $query = $conn->prepare("SELECT employee_rfid FROM employees_profile WHERE employee_id = ? AND employee_rfid = ?");
    $query->bind_param("is", $employeeId, $rfid);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false]);
    }
}
?>
