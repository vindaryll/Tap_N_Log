<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

$rfid = trim($_POST['rfid']);
$type_of_profile = trim($_POST['type_of_profile']);

// Map profile types to their respective tables and RFID column names
$table_mapping = [
    'CFW' => [
        'table' => 'cfw_profile',
        'rfid_column' => 'cfw_rfid',
    ],
    'OJT' => [
        'table' => 'ojt_profile',
        'rfid_column' => 'ojt_rfid',
    ],
    'EMPLOYEE' => [
        'table' => 'employees_profile',
        'rfid_column' => 'employee_rfid',
    ],
];

// Validate the profile type
if (!isset($table_mapping[$type_of_profile])) {
    echo json_encode(['success' => false, 'message' => 'Invalid profile type.']);
    exit();
}

$table_info = $table_mapping[$type_of_profile];
$table = $table_info['table'];
$rfid_column = $table_info['rfid_column'];

// If RFID is empty, return valid
if (empty($rfid)) {
    echo json_encode(['success' => true, 'exists' => false, 'message' => 'RFID is valid.']);
    exit();
}

// Check if RFID exists
$sql = "SELECT COUNT(*) AS count FROM $table WHERE $rfid_column = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $rfid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    echo json_encode(['success' => true, 'exists' => true, 'message' => 'RFID already exists.']);
} else {
    echo json_encode(['success' => true, 'exists' => false, 'message' => 'RFID is available.']);
}
?>
